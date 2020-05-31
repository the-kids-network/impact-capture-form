<?php

use App\Domains\SessionReports\Models\EmotionalState;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmotionalStateSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $this->addEmotionalState('Happy');
        $this->addEmotionalState('Sad');
        $this->addEmotionalState('Upset');
        $this->addEmotionalState('Angry');
        $this->addEmotionalState('Cheerful');
        $this->addEmotionalState('Talkative');
    }

    private function addEmotionalState($state) {
        $em = new EmotionalState();
        $em->name = $state;
        $em->save();
    }

}
