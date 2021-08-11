<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterColumnsInWithdraws extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('withdraws', function (Blueprint $table) {
            $table->dropColumn('jumlah_uang');
            $table->dropColumn('available_balance');
            $table->dropColumn('users_id');
            $table->integer('jumlah_tarik');
            $table->integer('percentage');
            $table->integer('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('withdraws', function (Blueprint $table) {
            $table->dropColumn('jumlah_tarik');
            $table->dropColumn('percentage');
            $table->dropColumn('user_id');
            $table->integer('jumlah_uang');
            $table->integer('available_balance');
            $table->integer('users_id');
        });
    }
}
