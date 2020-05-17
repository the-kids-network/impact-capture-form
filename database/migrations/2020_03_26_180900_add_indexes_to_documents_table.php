<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexesToDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documents', function(Blueprint $table)
        {
            $table->index('is_shared');
            $table->index('path');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('documents', function (Blueprint $table)
        {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $doctrineTable = $sm->listTableDetails('documents');

            if ($doctrineTable->hasIndex('is_shared')) {
                $table->dropIndex('is_shared');
            }
            if ($doctrineTable->hasIndex('path')) {
                $table->dropIndex('path');
            }
        });
    }
}
