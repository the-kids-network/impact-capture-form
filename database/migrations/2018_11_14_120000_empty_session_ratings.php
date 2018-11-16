<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EmptySessionRatings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('session_ratings')->insert(['value' => '', 'selectable' => false, 'id' => 0]);
        DB::update('update session_ratings set id=0 where value = ?', ['']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::delete('delete from session_ratings where value = ?', ['']);
    }
}
