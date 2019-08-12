<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenamePlannedSessionsColumns extends Migration
{
    public function up()
    {
        Schema::table('planned_sessions', function(Blueprint $table) {
            $table->renameColumn('next_session_date', 'date');
            $table->renameColumn('next_session_location', 'location');

        });
    }

    public function down()
    {
        Schema::table('planned_sessions', function(Blueprint $table) {
            $table->renameColumn('date', 'next_session_date');
            $table->renameColumn('location', 'next_session_location');
        });
    }
}
