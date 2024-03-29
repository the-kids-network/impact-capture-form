<?php

use App\Domains\SessionReports\Models\Report;
use App\Domains\UserManagement\Models\Mentee;
use App\Domains\UserManagement\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $todaysDate = Carbon::now()->toDateString();  

        $this->addReport('mentor-1', 'mentee-1', $todaysDate);
        $this->addReport('mentor-2', 'mentee-2', $todaysDate);
        $this->addReport('mentor-3', 'mentee-3', $todaysDate);
        $this->addReport('mentor-4', 'mentee-4', $todaysDate);
    }

    private function addReport($mentorName, $menteeName, $meetingDate) {
        $mentor = User::whereName($mentorName)->first();
        $mentee = Mentee::whereFirstName(explode('-', $menteeName)[0])->whereLastName(explode('-', $menteeName)[1])->first();

        $report = new Report();
        $report->mentor_id = $mentor->id;
        $report->mentee_id = $mentee->id;
        $report->session_date = $meetingDate;
        $report->length_of_session = 1;
        $report->activity_type_id = 1;
        $report->location = 'Location';
        $report->safeguarding_concern = false;
        $report->emotional_state_id = 1;
        $report->meeting_details = "Meeting between ".$mentorName." and ". $menteeName;
        $report->rating_id = 4;
        $report->save();
    }
}
