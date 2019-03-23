<?php

use Laravel\Spark\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Creating sample users...' . config('APP_ENV'));
        if (config('APP_ENV') != 'production') {
            // Admin
            $this->addUser(1, 'Admin Only','admin@example.com', 'admin', NULL);

            // Managers
            $this->addUser(2, 'Manager One','manager1@example.com', 'manager', NULL);
            $this->addUser(3, 'Manager Two','manager2@example.com', 'manager', NULL);

            // Finance
            $this->addUser(4, 'Finance One','finance1@example.com', 'finance', NULL);
            $this->addUser(5, 'Finance Two','finance2@example.com', 'finance', NULL);

            // Mentors
            $this->addUser(6, 'Mentor One','mentor1@example.com', NULL, 2);
            $this->addUser(7, 'Mentor Two','mentor2@example.com', NULL, 3);
        }
    }

    private function addUser($id, $name, $email, $role, $manager_id) {
        User::firstOrCreate([
            'id' => $id
        ], [
            'name' => $name,
            'email' => $email,
            'trial_ends_at' => '2018-02-21 00:25:39',
            'last_read_announcements_at' => '2018-02-21 00:25:39',
            'created_at' => '2018-02-21 00:25:39',
            'updated_at' => '2018-02-21 00:25:39',
            'role' => $role,
            'manager_id' => $manager_id,
            'password' => bcrypt('secret'),
        ]);
    }

}
