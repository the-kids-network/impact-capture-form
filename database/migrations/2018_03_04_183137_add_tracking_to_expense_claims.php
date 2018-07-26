<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTrackingToExpenseClaims extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expense_claims', function (Blueprint $table) {
            $table->integer('approved_by_id')->nullable();
            $table->integer('processed_by_id')->nullable();
            $table->date('approved_at')->nullable();
            $table->date('processed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('expense_claims', function (Blueprint $table) {
            $table->dropColumn('approved_by_id');
            $table->dropColumn('processed_by_id');
            $table->dropColumn('approved_at');
            $table->dropColumn('processed_at');
        });
    }
}
