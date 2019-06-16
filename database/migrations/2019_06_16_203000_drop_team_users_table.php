<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropTeamUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('team_users');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('team_users', function (Blueprint $table) {
            $table->integer('team_id');
            $table->integer('user_id');
            $table->string('role', 20);

            $table->unique(['team_id', 'user_id']);
        });
    }
}
