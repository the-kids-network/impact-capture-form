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
        $this->addReport(1, 21, 1, 'Meeting 1 Details', 1);
        $this->addReport(2, 20, 1, 'Meeting 2 Details', 6);
    }

    private function addReport($id, $mentor_id, $mentee_id, $meeting_details, $rating_id){
        $dt = new DateTime;

        DB::table('reports')->insert([
            'id' => $id,
            'mentor_id' => $mentor_id,
            'mentee_id' => $mentee_id,
            'session_date' => $dt->format('y-m-d'),
            'length_of_session' => 1,
            'activity_type_id' => 1,
            'location' => 'Location',
            'safeguarding_concern' => false,
            'physical_appearance_id' => 1,
            'emotional_state_id' => 1,
            'meeting_details' => $meeting_details,
            'rating_id' => $rating_id
        ]);
    }

}
