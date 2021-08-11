<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFundsRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('funds_request', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('users_id');
            $table->string('nomor_transaksi');
            $table->string('bank_tujuan', 100);
            $table->string('bank_name', 100)->nullable();
            $table->string('cabang', 100)->nullable();
            $table->string('nomor_rek', 50);
            $table->string('pemegang_bank', 50);
            $table->integer('jumlah_uang');
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('funds_request');
    }
}
