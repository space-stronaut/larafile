<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusFundReq extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('funds_request', function (Blueprint $table) {
            $table->enum('status', ['approved', 'pending', 'rejected', 'pending_payment', 'paid']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('funds_request', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
