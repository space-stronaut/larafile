<?php

namespace App\Http\Controllers\middle;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Category;
use App\Program;
use App\Development;
use App\DonationConfirmation;
use App\GlobalSetting;
use App\FundConfirm;
use App\User;
use App\Libs\ProgramLibs;

// Mail
use Illuminate\Support\Facades\Mail;
use App\Mail\FundsAdminEmail;

class programController extends Controller
{
    public function donasikonfir(Request $request, $id){
        $konfirmasi = DonationConfirmation::find($id);
        $collected = DonationConfirmation::where('program_id', $konfirmasi->program_id)->sum('nominal_donasi');
        $program = Program::where('id', $konfirmasi->program_id)->first();
        if($request->file('bukti_pembayaran')){
            $file       = $request->file('bukti_pembayaran');
            $fileName   = $file->getClientOriginalName();
            $request->file('bukti_pembayaran')->move("images/bukti_pembayaran/", $fileName);
            $bukti = $konfirmasi->bukti_pembayaran = $fileName;
            $konfirmasi->update(['isVerified' => 1, 'bukti_pembayaran' => $bukti]);
        }
        $program->update(['donation_collected' => $collected]);

        return redirect()->back();
    }

    public function donasi(){
        $info = DonationConfirmation::where('users_id', Auth::user()->id)->count();
        $donasiCount = DonationConfirmation::where('users_id', Auth::user()->id)->where('isVerified', 1)->count(); 
        $konfirCount = DonationConfirmation::where('users_id', Auth::user()->id)->where('isVerified', 0)->count();
        $donasi = DonationConfirmation::where('users_id', Auth::user()->id)->where('isVerified', 1)->get(); 
        $konfir = DonationConfirmation::where('users_id', Auth::user()->id)->where('isVerified', 0)->get();
        return view('middle.donasi', compact('konfir', 'donasi', 'info', 'donasiCount', 'konfirCount'));
    }

    public function verify($id){
        $verify = DonationConfirmation::find($id);
        $verify->update(['isVerified' => 1]);

        return redirect()->back();
    }

    public function createlaporanperkembangan($id){
        $program = Program::find($id);
        return view('middle.createdevelopment', ['program' => $program]);
    }
    
    public function storelaporanperkembangan(Request $request){
        $dev = Development::create($request->all());
        return redirect()->route('detail', ['id' => $dev->program_id]);
    }



    public function detailprogram($id){
        $program = Program::find($id);
        $donaturs = $program->donatur()->paginate(10);
        
        $no_rek = \App\GlobalSetting::find(1);
        $devs = $program->development();

        $donationdata = ProgramLibs::getDonationData($program->id);
        $collected = $donationdata['collected'];
        $available_balance = $donationdata['available_balance'];
        $withdrawal = $donationdata['withdrawal'];

        return view('middle.detailprogram', compact(
            'program', 'no_rek','devs', 'donaturs', 'collected', 'withdrawal', 'available_balance'
        ));
    }
    

    public function middle(){
        $program = Program::where('users_id', Auth::user()->id)->count();
        $programPublished = Program::where('users_id', Auth::user()->id)->where('isPublished', 1)->count();
        $programNotPublished = Program::where('users_id', Auth::user()->id)->where('isPublished', 0)->count();
        $donasi = DonationConfirmation::where('users_id', Auth::user()->id)->count();
        $konfir = DonationConfirmation::where('users_id', Auth::user()->id)->where('isVerified', 0)->count(); 
        
        return view('middle.index', compact('program', 'programPublished', 'programNotPublished', 'donasi', 'konfir'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        
        $info = Program::where('users_id', Auth::user()->id)->count();
        $programs = Program::where('users_id', Auth::user()->id)->orderBy('isPublished', 'DESC')->get();
        // if time is up, this destroy
       return view('middle.program', compact('programs', 'info'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('middle.create', ['categories' => $categories]);
    }

    
    public function formCairDana($id){
        $program = Program::find($id);

        $devs = Development::where('program_id', $program->id)->get();
        // $donatur = DonationConfirmation::where('program_id', $id)->count();
        // $konfirCount = DonationConfirmation::where('users_id', Auth::user()->id)->count();
        $collected = DonationConfirmation::where('program_id', $id)->sum('nominal_donasi');
        $categories = Category::find($program->category_id);
        $globalsetting = \App\GlobalSetting::find(1);
        // dd($globalsetting);
        
        // Mengurangi jumlah yang sudah ada di program dengan jumlah cair dana yang akan masuk

        $program_collected = $program->donation_collected;
        $withdrawal = $collected - $program_collected;
        
        
        
        return view('formcair',  compact('program', 'devs', 'collected', 'globalsetting', 'categories', 'withdrawal'));
    }

    public function createAjukanDana(Request $request)
    {        
        $ajuan = new \App\FundConfirm;
        

        $id_donatur_terakhir = $ajuan->max('id');
        $kode_awal = date("ymd");
        $id_program = $request->program_id;
        $nomor_transaksi = $kode_awal . sprintf(abs($id_program + 2)) . sprintf("%03s", abs($id_donatur_terakhir + 1));

        $ajuan->nomor_transaksi = $nomor_transaksi;
        $ajuan->users_id = Auth::user()->id;
        $ajuan->bank_tujuan = $request->bank_tujuan;
        $ajuan->nomor_rek = $request->nomor_rek;
        $ajuan->pemegang_bank = $request->pemegang_bank;
        $ajuan->jumlah_tarik = $request->total_dibayar;
        $ajuan->programs_id = $request->program_id;
        $ajuan->available_balance = $request->available_balance;
        
        if($request->bank_name !== null && $request->cabang !== null){
            $ajuan->bank_name = $request->bank_name;
            $ajuan->cabang = $request->cabang;
        }
        $ajuan->status = "pending";   
        
        
        // Auto Pause saat User mengirimkan Ajuan dana
        $program = Program::find($id_program);
        $user = User::find($ajuan->users_id);
        Mail::to($user->email)->send(new FundsAdminEmail($user->name, $user->email, $request->total_dibayar));
        $ajuan->save();
        $program->update(['status' => 'pause']);



        return redirect('withdraw/list');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate( request() , [
            'title' => ['required', 'max:100'],
            'donation_target' => ['required', 'numeric'],
            'brief_explanation' => ['required', 'max:200'],
            'donation_target' => ['required', 'min:7'],
            'description' => ['required'],
        ]);

        $program = Program::create($request->all());
        if($request->hasFile('photo'))
        {
            $request->file('photo')->move('images/program-images/', $request->file('photo')->getClientOriginalName());
            $program->photo = $request->file('photo')->getClientOriginalName();
            
        }

        $program->status = 'pending';
        $program->save();

        return redirect('/program');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categories = Category::all();
        $program = Program::find($id);
        return view('middle.edit', compact('program', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $program = Program::find($id);
        $program->update($request->all());
        if($request->hasFile('photo'))
        {
            $request->file('photo')->move('images/program-images/', $request->file('photo')->getClientOriginalName());
            $program->photo = $request->file('photo')->getClientOriginalName();
            $program->update();
        }

        return redirect('/program');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Program::destroy($id);
        return redirect()->back();
    }
}
