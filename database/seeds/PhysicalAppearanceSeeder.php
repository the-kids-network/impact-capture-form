<?php

use Illuminate\Database\Seeder;

class PhysicalAppearanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->addPhysicalApperance('Strong');
        $this->addPhysicalApperance('Fit');
        $this->addPhysicalApperance('Healthy');
        $this->addPhysicalApperance('Short');
        $this->addPhysicalApperance('Skinny');
        $this->addPhysicalApperance('Weak');
        $this->addPhysicalApperance('Fat');
        $this->addPhysicalApperance('Big');
    }

    private function addPhysicalApperance($state){
        DB::table('physical_appearances')->insert([
            'name' => $state,
            'created_at' => '2018-02-08 20:07:39',
            'updated_at' => '2018-02-08 20:07:39'
        ]);
    }
}
