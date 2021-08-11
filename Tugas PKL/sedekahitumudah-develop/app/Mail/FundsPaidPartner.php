<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class FundsPaidPartner extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $email, $uang)
    {
        //        
        $this->name = $name;
        $this->email = $email;
        $this->uang = $uang;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('[SedekahituMudah] Ajuan Pencairan Dana Sudah Dibayar')->view('emails.form_partner_paid')->with(
            [
                'name' => $this->name,
                'email' => $this->email,
                'balance' => $this->uang    
            ]
        );
    }
}
