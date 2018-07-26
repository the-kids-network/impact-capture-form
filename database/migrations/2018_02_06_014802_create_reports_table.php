<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mentor_id');
            $table->integer('mentee_id');
            $table->date('session_date');
            $table->float('length_of_session');
            $table->integer('activity_type_id');
            $table->string('location');
            $table->boolean('safeguarding_concern');
            $table->integer('physical_appearance_id');
            $table->integer('emotional_state_id');
            $table->text('meeting_details');
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
        Schema::dropIfExists('reports');
    }
}
