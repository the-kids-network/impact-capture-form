<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expense_claims', function (Blueprint $table) {
            $table->foreign('mentor_id')->references('id')->on('users');
            $table->foreign('report_id')->references('id')->on('reports');
            $table->foreign('approved_by_id')->references('id')->on('users');
            $table->foreign('processed_by_id')->references('id')->on('users');
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->foreign('expense_claim_id')->references('id')->on('expense_claims');
        });

        Schema::table('mentees', function (Blueprint $table) {
            $table->foreign('mentor_id')->references('id')->on('users');
        });

        Schema::table('receipts', function (Blueprint $table) {
            $table->foreign('expense_claim_id')->references('id')->on('expense_claims');
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->foreign('mentor_id')->references('id')->on('users');
            $table->foreign('mentee_id')->references('id')->on('users');
            $table->foreign('activity_type_id')->references('id')->on('activity_types');
            $table->foreign('physical_appearance_id')->references('id')->on('physical_appearances');
            $table->foreign('emotional_state_id')->references('id')->on('emotional_states');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('manager_id')->references('id')->on('users');
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
            $table->dropForeign(['mentor_id']);
            $table->dropForeign(['report_id']);
            $table->dropForeign(['approved_by_id']);
            $table->dropForeign(['processed_by_id']);
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->dropForeign(['expense_claim_id']);
        });

        Schema::table('mentees', function (Blueprint $table) {
            $table->dropForeign(['mentor_id']);
        });

        Schema::table('receipts', function (Blueprint $table) {
            $table->dropForeign(['expense_claim_id']);
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign(['mentor_id']);
            $table->dropForeign(['mentee_id']);
            $table->dropForeign(['activity_type_id']);
            $table->dropForeign(['physical_appearance_id']);
            $table->dropForeign(['emotional_state_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['manager_id']);
        });
    }
}
