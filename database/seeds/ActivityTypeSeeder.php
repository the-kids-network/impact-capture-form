<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivityTypeSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $this->addActivity('Basketball');
        $this->addActivity('Baseball');
        $this->addActivity('Tennis');
        $this->addActivity('Museum');
        $this->addActivity('Walk');
        $this->addActivity('Park');
    }

    private function addActivity($activity) {
        DB::table('activity_types')->insert([
            'name' => $activity,
            'created_at' => '2018-02-08 20:07:39',
            'updated_at' => '2018-02-08 20:07:39'
        ]);
    }
}
