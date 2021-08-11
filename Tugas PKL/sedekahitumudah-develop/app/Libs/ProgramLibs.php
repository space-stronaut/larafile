<?php
namespace App\Libs;

use App\Program;

class ProgramLibs
{
    public static function getDonationData($id)
    {
        $program = Program::find($id);

        $collected = $program->donatur()->sum('nominal_donasi');
        $withdrawal = $program->withdraw()->where('status', 'paid')->sum('jumlah_tarik');
        
        $available_balance = $collected - $withdrawal;

        return [
            'collected' => $collected,
            'withdrawal' => $withdrawal,
            'available_balance' => $available_balance,
        ];
    }
}
