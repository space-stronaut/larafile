<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteTableWithdraw extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('withdraw');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('withdraw', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('program_id');
            $table->string('bank_name', 100);
            $table->string('program_name', 100);
            $table->integer('jumlah_tarik');
            $table->timestamps();
        });
    }
}
