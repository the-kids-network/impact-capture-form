<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ActivityTypeSeeder::class);
        $this->call(EmotionalStateSeeder::class);
        $this->call(PhysicalAppearanceSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(MenteeTableSeeder::class);
        $this->call(ReportTableSeeder::class);
    }
}
