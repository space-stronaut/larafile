<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameProgramsId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('funds_request', function (Blueprint $table) {
            $table->dropColumn('program_id');
        });
        Schema::table('funds_request', function (Blueprint $table) {
            $table->integer('programs_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('funds_request', function (Blueprint $table) {
            $table->dropColumn('programs_id');
        });
        Schema::table('funds_request', function (Blueprint $table) {
            $table->integer('program_id');
        });
    }
}
