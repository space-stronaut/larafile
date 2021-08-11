<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class FundsAdminEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $email, $jumlah)
    {
        //
        $this->name = $name;
        $this->email = $email;
        $this->jumlah = $jumlah;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('[SedekahituMudah] Partner Merequest Ajuan Pencairan Dana')->view('emails.form_admin_ajuan')->with(
            [
                'name' => $this->name,
                'email' => $this->email,
                'jumlah' => $this->jumlah
            ]
        );
    }
}
