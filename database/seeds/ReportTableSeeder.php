<?php

use Illuminate\Database\Seeder;

class ReportTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->addReport(12, 1, 'Meeting 1 Details', 1);
        $this->addReport(13, 2, 'Meeting 2 Details', 2);
        $this->addReport(14, 3, 'Meeting 3 Details', 3);

    }

    private function addReport($mentor_id, $mentee_id, $meeting_details, $rating_id){
        $dt = new DateTime;

        DB::table('reports')->insert([
            'mentor_id' => $mentor_id,
            'mentee_id' => $mentee_id,
            'session_date' => $dt->format('Y-m-d'),
            'length_of_session' => 1,
            'activity_type_id' => 1,
            'location' => 'Location',
            'safeguarding_concern' => false,
            'emotional_state_id' => 1,
            'meeting_details' => $meeting_details,
            'rating_id' => $rating_id
        ]);
    }

}
