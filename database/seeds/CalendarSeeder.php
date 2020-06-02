<?php

use App\Domains\Calendar\Models\MenteeLeave;
use App\Domains\Calendar\Models\MentorLeave;
use App\Domains\Calendar\Models\PlannedSession;
use App\Mentee;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class CalendarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $todaysDate = Carbon::today()->toDateString();  
        $tomorrowsDate = Carbon::tomorrow()->toDateString();

        $this->addPlannedSession('mentor-1', 'mentee-1', $todaysDate, 'Park');
        $this->addPlannedSession('mentor-2', 'mentee-2', $todaysDate, 'Museum');
        $this->addPlannedSession('mentor-3', 'mentee-3', $todaysDate, 'Cinema');
        $this->addPlannedSession('mentor-4', 'mentee-4', $todaysDate, 'Coffee shop');

        $this->addMentorLeave("mentor-1", $todaysDate, $tomorrowsDate);
        $this->addMentorLeave("mentor-2", $todaysDate, $tomorrowsDate);
        $this->addMentorLeave("mentor-3", $todaysDate, $tomorrowsDate);

        $this->addMenteeLeave("mentee-1", $todaysDate, $tomorrowsDate);
        $this->addMenteeLeave("mentee-2", $todaysDate, $tomorrowsDate);
        $this->addMenteeLeave("mentee-3", $todaysDate, $tomorrowsDate);
    }

    private function addPlannedSession($mentorName, $menteeName, $meetingDate, $location) {
        $mentor = User::whereName($mentorName)->first();
        $mentee = Mentee::whereFirstName(explode('-', $menteeName)[0])->whereLastName(explode('-', $menteeName)[1])->first();

        $event = new PlannedSession();
        $event->mentee_id = $mentee->id;
        $event->date = $meetingDate;
        $event->location = $location;
       
        $event->save();
    }

    private function addMentorLeave($mentorName, $startDate, $endDate) {
        $mentor = User::whereName($mentorName)->first();

        $event = new MentorLeave();
        $event->mentor_id = $mentor->id;
        $event->start_date = $startDate;
        $event->end_date = $endDate;

        $event->save();
    }

    private function addMenteeLeave($menteeName, $startDate, $endDate) {
        $mentee = Mentee::whereFirstName(explode('-', $menteeName)[0])->whereLastName(explode('-', $menteeName)[1])->first();

        $event = new MenteeLeave();
        $event->mentee_id = $mentee->id;
        $event->start_date = $startDate;
        $event->end_date = $endDate;

        $event->save();
    }
}
