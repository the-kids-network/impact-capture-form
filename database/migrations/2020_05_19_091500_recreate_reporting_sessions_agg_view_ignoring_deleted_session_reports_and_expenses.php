<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class RecreateReportingSessionsAggViewIgnoringDeletedSessionReportsAndExpenses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement($this->dropView());
        DB::statement($this->createView());
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement($this->dropView());
    }

    /**
     * Create and populate view
     */
    private function createView(): string
    {
        return <<<SQL
CREATE VIEW reporting_sessions AS
SELECT 
    u.id as user_id,
    s.report_id,
    s.session_date,
    s.session_length,
    COALESCE(e.expenses_total, 0) AS expenses_total,
    COALESCE(e.expenses_pending,0) AS expenses_pending,
    COALESCE(e.expenses_rejected,0) AS expenses_rejected,
    COALESCE(e.expenses_processed,0) AS expenses_processed
FROM users u
JOIN (
    /* join sessions */
    SELECT DISTINCT
        mentor_id AS mentor_id,
        id AS report_id,
        session_date AS session_date,
        length_of_session AS session_length
    FROM reports
    WHERE deleted_at IS NULL
) s ON s.mentor_id=u.id 
LEFT JOIN (
    /* join aggregated expense amounts to session */
    SELECT DISTINCT 
        report_id,
        SUM(expenses_total) AS expenses_total,
        SUM(expenses_pending) AS expenses_pending,
        SUM(expenses_rejected) AS expenses_rejected,
        SUM(expenses_processed) AS expenses_processed
    FROM (
        SELECT DISTINCT
            ec.report_id AS report_id,
            (SELECT SUM(amount) AS total_expenses) AS expenses_total,
            (SELECT SUM(amount) AS total_expenses WHERE status = 'pending') AS expenses_pending,
            (SELECT SUM(amount) AS total_expenses WHERE status = 'rejected') AS expenses_rejected,
            (SELECT SUM(amount) AS total_expenses WHERE status = 'processed') AS expenses_processed
        FROM expense_claims ec 
        JOIN expenses e ON e.expense_claim_id=ec.id 
        WHERE ec.deleted_at IS NULL
        AND e.deleted_at IS NULL
        GROUP BY report_id, status
    ) as exp_agg
    GROUP BY report_id
) e ON e.report_id=s.report_id
SQL;
    }

    /**
     * Drop view
     */
    private function dropView(): string 
    {
        return <<<SQL
DROP VIEW IF EXISTS reporting_sessions;
SQL;
    }
}