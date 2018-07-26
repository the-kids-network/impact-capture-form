<?php

use Illuminate\Database\Seeder;

class MenteesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->addMentee('Billy','Fleming');
        $this->addMentee('Lillie','Bailey');
        $this->addMentee('Phyllis','Christensen');
        $this->addMentee('Chester','Buchanan');
        $this->addMentee('Anita','Weaver');
        $this->addMentee('George','Buchanan');
    }

    private function addMentee($first, $last){

        DB::table('mentees')->insert([
            'first_name' => $first,
            'last_name' => $last,
            'created_at' => '2018-02-08 20:07:39',
            'updated_at' => '2018-02-08 20:07:39'
        ]);

    }
}
