<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    protected $fillable = [
        'bank_tujuan',
        'bank_name',
        'cabang',
        'nomor_rek',
        'pemegang_bank',
        'jumlah_tarik',
        'pemegang_bank',
        'program_id',
        'user_id',
        'status',
        'alasan',
    ];

    public function program(){
        return $this->belongsTo('App\Program');
    }
    
    public function user(){
        return $this->belongsTo('App\User');
    }
}
