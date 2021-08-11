<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEmailInDonationConfirmations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('donation_confirmations', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
            $table->text('dukungan')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('donation_confirmations', function (Blueprint $table) {
            $table->string('email')->nullable(false)->change();
            $table->text('dukungan')->nullable(false)->change();
        });
    }
}
