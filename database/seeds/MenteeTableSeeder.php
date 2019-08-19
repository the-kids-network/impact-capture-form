<?php

use Illuminate\Database\Seeder;

class MenteeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->addMentee(1, 'Mentee', 'One', 12);
        $this->addMentee(2, 'Mentee', 'Two', 13);
        $this->addMentee(3, 'Mentee', 'Three', 14);
    }

    private function addMentee($id, $first_name, $last_name, $mentor_id){
        DB::table('mentees')->insert([
            'id' => $id,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'mentor_id' => $mentor_id
        ]);
    }

}
