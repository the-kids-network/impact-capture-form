<?php

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
        DB::table('emotional_states')->insert([
            'name' => $state,
            'created_at' => '2018-02-08 20:07:39',
            'updated_at' => '2018-02-08 20:07:39'
        ]);
    }

}
