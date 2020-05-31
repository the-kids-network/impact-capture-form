<?php

use App\Domains\SessionReports\Models\ActivityType;
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
        $act = new ActivityType();
        $act->name = $activity;
        $act->save();
    }
}
