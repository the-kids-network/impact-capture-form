<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportingMentorsAggView extends Migration
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
CREATE VIEW reporting_mentors AS
SELECT DISTINCT
    u.id as mentor_id,
    u.name as mentor_name,
    man.id as manager_id,
    man.name as manager_name,
    s.start_date,
    s.last_session_date,
    s.days_since_last_session,
    m.next_scheduled_session
FROM users u
LEFT JOIN (
    /* join manager details*/
    SELECT DISTINCT
        id,
        name
    FROM users
) man ON man.id=u.manager_id
LEFT JOIN (
    /* join start date and session data */
    SELECT DISTINCT
        mentor_id,
        MIN(session_date) AS start_date,
        MAX(session_date) AS last_session_date,
        datediff(now(), MAX(session_date)) AS days_since_last_session
    FROM reports
    GROUP BY mentor_id
) s ON s.mentor_id=u.id
LEFT JOIN (
    /* join schedule data */
    SELECT DISTINCT
        m.mentor_id,
        MIN(s.next_session_date) as next_scheduled_session
    FROM mentees m   
    JOIN schedules s ON m.id=s.mentee_id
    WHERE s.next_session_date >= now()
    GROUP BY m.mentor_id
) m ON m.mentor_id=u.id
WHERE 1=1
AND u.role IS NULL
AND u.deleted_at IS NULL  
SQL;
    }

    /**
     * Drop view
     */
    private function dropView(): string 
    {
        return <<<SQL
DROP VIEW IF EXISTS reporting_mentors;
SQL;
    }
}
