<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexesToSessionReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reports', function(Blueprint $table)
        {
            $table->index('mentor_id');
            $table->index('mentee_id');
            $table->index('safeguarding_concern');
            $table->index('emotional_state_id');
            $table->index('rating_id');
            $table->index('session_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reports', function (Blueprint $table)
        {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $doctrineTable = $sm->listTableDetails('reports');

            if ($doctrineTable->hasIndex('mentor_id')) {
                $table->dropIndex('mentor_id');
            }
            if ($doctrineTable->hasIndex('mentee_id')) {
                $table->dropIndex('mentee_id');
            }
            if ($doctrineTable->hasIndex('safeguarding_concern')) {
                $table->dropIndex('safeguarding_concern');
            }
            if ($doctrineTable->hasIndex('emotional_state_id')) {
                $table->dropIndex('emotional_state_id');
            }
            if ($doctrineTable->hasIndex('rating_id')) {
                $table->dropIndex('rating_id');
            }
            if ($doctrineTable->hasIndex('seesion_date')) {
                $table->dropIndex('session_date');
            }
        });
    }
}
