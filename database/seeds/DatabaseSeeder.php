<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('expenses')->truncate();
        DB::table('expense_claims')->truncate();
        DB::table('receipts')->truncate();
        DB::table('reports')->truncate();
        DB::table('users')->truncate();
        DB::table('mentees')->truncate();
        DB::table('activity_types')->truncate();
        DB::table('emotional_states')->truncate();


        $this->call(ActivityTypeSeeder::class);
        $this->call(EmotionalStateSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(ReportTableSeeder::class);
        $this->call(ExpenseClaimSeeder::class);
    }
}
