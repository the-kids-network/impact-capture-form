<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexes2ToExpenseClaimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expense_claims', function(Blueprint $table)
        {
            $table->index('processed_by_id');
            $table->index('status');
            $table->index('created_at');
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
            $doctrineTable = $sm->listTableDetails('expense_claims');

            if ($doctrineTable->hasIndex('processed_by_id')) {
                $table->dropIndex('processed_by_id');
            }

            if ($doctrineTable->hasIndex('status')) {
                $table->dropIndex('status');
            }

            if ($doctrineTable->hasIndex('created_at')) {
                $table->dropIndex('created_at');
            }
        });
    }
}
