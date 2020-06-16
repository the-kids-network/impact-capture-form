<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexesToExpenseClaimsTable extends Migration
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
            $table->index('report_id');
            $table->index('mentor_id');
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

            if ($doctrineTable->hasIndex('report_id')) {
                $table->dropIndex('report_id');
            }

            if ($doctrineTable->hasIndex('mentor_id')) {
                $table->dropIndex('mentor_id');
            }
        });
    }
}
