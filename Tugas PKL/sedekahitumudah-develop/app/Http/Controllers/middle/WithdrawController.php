<?php

namespace App\Http\Controllers\middle;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use Auth;

use App\Mail\FundsAdminEmail;
use App\Libs\ProgramLibs;
use App\Program;
use App\Withdraw;
use App\Category;
use App\User;

class WithdrawController extends Controller
{
    public $withdraw_status = [
        'pending' => 'Menunggu',
        'approved' => 'Disetujui',
        'pending_payment' => 'Sedang diproses',
        'paid' => 'Sudah dibayar',
        'reject' => 'Ditolak',
    ];

    public $bank_tujuan = [
        'mandiri' => 'Mandiri',
        'bri' => 'BRI',
        'bni' => 'BNI',
        'bca' => 'BCA',
        'syariah_mandiri' => 'Bank Syariah Mandiri',
        'cimb' => 'CIMB Niaga',
        'muamalat' => 'Muamalat',
        'btpn' => 'BTPN / Jenius / BPTN Wow!',
        'permata' => 'Permata / Permata Syariah',
        'bri_syariah' => 'BRI Syariah',
        'digibank' => 'Digibank / DBS',
        'misc' => 'Bank Lainnya',
    ];

    public function index(Request $request){
        $withdraw_status = $this->withdraw_status;
        $bank_tujuan = $this->bank_tujuan;

        $withdraws = Withdraw::where('user_id', Auth::user()->id);

        if ($request->get('q')){
            $q = $request->get('q');
            $withdraws = Withdraw::where('user_id', Auth::user()->id)
                ->where('nomor_transaksi', 'like', '%'.$q.'%')
                ->orWhere('jumlah_tarik', 'like', '%'.$q.'%')
                ->orWhere('created_at', 'like', '%'.$q.'%')
                ->orWhere('nomor_rek', 'like', '%'.$q.'%')
                ->orWhere('pemegang_bank', 'like', '%'.$q.'%')
                ->orWhere('bank_tujuan', 'like', '%'.$q.'%')
                ->orWhere('bank_name', 'like', '%'.$q.'%')
                ->orWhere('cabang', 'like', '%'.$q.'%');
        }

        $total_data = $withdraws->count();
        $withdraws = $withdraws->paginate(20);

        foreach ($withdraws as &$row) {
            if ($row->bank_tujuan!='misc'){
                $row->bank_name = $bank_tujuan[$row->bank_tujuan];
            }
        }

        return view('middle.withdraw.list', compact('withdraws', 'total_data', 'withdraw_status', 'bank_tujuan'));
    }

    public function form($id)
    {
        $program = Program::find($id);
        $bank_tujuan = $this->bank_tujuan;

        $donationdata = ProgramLibs::getDonationData($program->id);
        $available_balance = $donationdata['available_balance'];

        return view('middle.withdraw.form',  compact(
            'program', 'available_balance', 'bank_tujuan'
        ));
    }

    public function preview(Request $request)
    {        
        $id = $request->program_id;
        $program = Program::find($id);
        $input = $request->all();

        $bank_tujuan = $this->bank_tujuan;
        $jumlah_tarik = $request->jumlah_tarik;

        $donationdata = ProgramLibs::getDonationData($program->id);
        $collected = $donationdata['collected'];
        $available_balance = $donationdata['available_balance'];
        $withdrawal = $donationdata['withdrawal'];

        $validator = Validator::make($request->all(), [
            'jumlah_tarik' => 'lte:'. $available_balance,
        ],[
            'jumlah_tarik.lte' => 'Jumlah dana yang akan ditarik tidak boleh lebih dari jumlah dana yang bisa dicairkan (Rp. '.number_format($available_balance, 0, '', '.').',-)',
        ]);

        if ($validator->fails()) {
            return redirect('withdraw/form/'.$program->id)
                ->withErrors($validator)
                ->withInput();
        }

        $category = Category::find($program->category_id);
        $globalsetting = \App\GlobalSetting::find(1);

        if ($program->ops_percentage !== null) {
            $percentage = $program->ops_percentage;    
        } elseif ($category->ops_percentage !== null) {
            $percentage = $category->ops_percentage;
        } elseif ($globalsetting->persen !== null) {
            $percentage = $globalsetting->persen;
        }

        $potongan_operasional = ($percentage/100) * $jumlah_tarik;

        $withdrawal_final = $jumlah_tarik - $potongan_operasional;

        return view('middle.withdraw.preview',  compact(
            'program', 'collected', 'withdrawal', 'available_balance', 'jumlah_tarik',
            'potongan_operasional', 'withdrawal_final', 'percentage', 'input', 'bank_tujuan'
        ));
    }

    public function create(Request $request)
    {        
        $id = $request->program_id;
        $program = Program::find($id);

        $ajuan = new Withdraw;
        

        $id_donatur_terakhir = $ajuan->max('id');
        $kode_awal = date("ymd");
        $id_program = $request->program_id;
        $nomor_transaksi = $kode_awal . sprintf(abs($id_program + 2)) . sprintf("%03s", abs($id_donatur_terakhir + 1));

        $ajuan->nomor_transaksi = $nomor_transaksi;
        $ajuan->user_id = Auth::user()->id;
        $ajuan->bank_tujuan = $request->bank_tujuan;
        $ajuan->nomor_rek = $request->nomor_rek;
        $ajuan->pemegang_bank = $request->pemegang_bank;
        $ajuan->jumlah_tarik = $request->jumlah_tarik;
        $ajuan->program_id = $request->program_id;
        $ajuan->percentage = $request->percentage;
        
        if($request->bank_name !== null && $request->cabang !== null){
            $ajuan->bank_name = $request->bank_name;
            $ajuan->cabang = $request->cabang;
        }

        $ajuan->status = "pending";   
        
        $program = Program::find($id_program);
        $user = User::find($ajuan->user_id);
        Mail::to($user->email)->send(new FundsAdminEmail($user->name, $user->email, $request->jumlah_tarik));

        $ajuan->save();

        // Auto Pause program saat User mengajukan pencairan dana
        $program->update(['status' => 'pause']);

        return redirect('withdraw/list');
    }

    public function view($id)
    {
        $withdraw_status = $this->withdraw_status;
        $bank_tujuan = $this->bank_tujuan;

        $withdraw = Withdraw::where('id', $id)->where('user_id', Auth::user()->id)->first();
        if ($withdraw == null){
            return redirect('withdraw/list');
        }

        $id = $withdraw->program_id;
        $program = Program::find($id);
        $jumlah_tarik = $withdraw->jumlah_tarik;

        $category = Category::find($program->category_id);
        $globalsetting = \App\GlobalSetting::find(1);

        $potongan_operasional = ($withdraw->percentage/100) * $withdraw->jumlah_tarik;

        $withdrawal_final = $withdraw->jumlah_tarik - $potongan_operasional;

        return view('middle.withdraw.view',  compact(
            'withdraw', 'program', 'potongan_operasional', 'withdrawal_final', 'bank_tujuan', 'withdraw_status'
        ));
    }
}