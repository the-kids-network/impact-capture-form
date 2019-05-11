<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportingSessionsAggView extends Migration
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
    COALESCE(s.session_count, 0) AS session_count,
    COALESCE(s.length_of_session, 0) AS session_length,
    COALESCE(e.expenses_total, 0) AS expenses_total,
    COALESCE(e.expenses_pending,0) AS expenses_pending,
    COALESCE(e.expenses_approved,0) AS expenses_approved,
    COALESCE(e.expenses_rejected,0) AS expenses_rejected
FROM users u
JOIN (
    /* join sessions */
    SELECT DISTINCT
        mentor_id AS mentor_id,
        id AS report_id,
        session_date AS session_date,
        1 AS session_count,
        length_of_session AS length_of_session
    FROM reports
) s ON s.mentor_id=u.id 
LEFT JOIN (
    /* join aggregated expense amounts to sessions */
    SELECT DISTINCT
        ec.report_id AS report_id,
        (SELECT SUM(amount) AS total_expenses) AS expenses_total,
        (SELECT SUM(amount) AS total_expenses WHERE status = 'rejected') AS expenses_rejected,
        (SELECT SUM(amount) AS total_expenses WHERE status = 'pending') AS expenses_pending,
        (SELECT SUM(amount) AS total_expenses WHERE status = 'approved') AS expenses_approved
    FROM expense_claims ec 
    JOIN expenses e ON e.expense_claim_id=ec.id 
    GROUP BY report_id, status
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