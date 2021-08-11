<?php

namespace App\Http\Controllers\back;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Withdraw;
use App\DonationConfirmation;
use App\Program;
use App\Category;
use App\User;
use App\Development;
use App\Report;
use App\GlobalSetting;

// Mail
use Illuminate\Support\Facades\Mail;
use App\Mail\FundsApprovePartner;
use App\Mail\FundsPaidPartner;

class WithdrawAdminController extends Controller
{
    //
    public $funds_status = [
        'pending' => 'Menunggu',
        'approved' => 'Disetujui',
        'pending_payment' => 'Sedang diproses',
        'paid' => 'Sudah dibayar',
        'rejected' => 'Ditolak',
    ];

    public function index()
    {        
        $fundsReq = Withdraw::all();

        foreach ($fundsReq as &$funds){
            $funds->potongan = 0;
            if ($funds->percentage > 0){
                $funds->potongan = ($funds->percentage/100) * $funds->jumlah_tarik;
            }
            $funds->jumlah_terima = $funds->jumlah_tarik - $funds->potongan;
        }

        return view('back.withdraw.list', compact('fundsReq'));
    }

    public function updateStatus($id, $status, Request $request)
    {
        $funds_status = array_keys($this->funds_status);
        
        if (in_array($status, $funds_status)){
            $fundsReq = Withdraw::find($id);
            $user = User::find($fundsReq->user_id);
            
            if ($status == 'approved'){
                Mail::to($user->email)->send(new FundsApprovePartner($user->name, $user->email));
                $fundsReq->update(['status' => 'pending_payment']);

            } else if ($status == 'paid'){
                $fundsReq->update(['status' => 'paid']);

                // aktifkan kembali program
                $program = Program::find($fundsReq->program_id);
                $program->update(['status' => 'active']);

                Mail::to($user->email)->send(new FundsPaidPartner($user->name, $user->email, $fundsReq->jumlah_tarik));
            }
            return redirect()->back();
        } else {
            return redirect()->with(['error' => 'Invalid status'])->back();
        }
    }


    // Reject Status
    public function rejectStatus($id, Request $request, $status)
    {
        $funds_status = array_keys($this->funds_status);
                
        if (in_array($status, $funds_status)){
            $fundsReq = Withdraw::find($id);

            $fundsReq->update(['alasan' => $request->alasan]);            
            $fundsReq->update(['status' => $status]);

            return redirect()->back();
        } else {
            return redirect()->with(['error' => 'Invalid status'])->back();
        }

        return redirect()->back();

    }

    
}
