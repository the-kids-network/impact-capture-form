<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexesToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function(Blueprint $table)
        {
            $table->index('email');
            $table->index('role');
            $table->index('manager_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('expense_claims', function (Blueprint $table)
        {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $doctrineTable = $sm->listTableDetails('users');

            if ($doctrineTable->hasIndex('email')) {
                $table->dropIndex('email');
            }

            if ($doctrineTable->hasIndex('role')) {
                $table->dropIndex('role');
            }

            if ($doctrineTable->hasIndex('manager_id')) {
                $table->dropIndex('manager_id');
            }
        });
    }
}
