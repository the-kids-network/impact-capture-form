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
            // One Admin
            $this->addUser(1, 'Admin Only','admin@example.com', 'admin', NULL);

            // Five Managers
            $this->addUser(2, 'Manager One','manager1@example.com', 'manager', NULL);
            $this->addUser(3, 'Manager Two','manager2@example.com', 'manager', NULL);
            $this->addUser(4, 'Manager Three','manager3@example.com', 'manager', NULL);
            $this->addUser(5, 'Manager Four','manager4@example.com', 'manager', NULL);
            $this->addUser(6, 'Manager Five','manager5@example.com', 'manager', NULL);

            // Five Finance
            $this->addUser(7, 'Finance One','finance1@example.com', 'finance', NULL);
            $this->addUser(8, 'Finance Two','finance2@example.com', 'finance', NULL);
            $this->addUser(9, 'Finance Three','finance3@example.com', 'finance', NULL);
            $this->addUser(10, 'Finance Four','finance4@example.com', 'finance', NULL);
            $this->addUser(11, 'Finance Five','finance5@example.com', 'finance', NULL);

            // Ten Mentors
            $this->addUser(12, 'Mentor One','mentor1@example.com', NULL, 2);
            $this->addUser(13, 'Mentor Two','mentor2@example.com', NULL, 3);
            $this->addUser(14, 'Mentor Three','mentor3@example.com', NULL, 3);
            $this->addUser(15, 'Mentor Four','mentor4@example.com', NULL, NULL);
            $this->addUser(16, 'Mentor Five','mentor5@example.com', NULL, NULL);
            $this->addUser(17, 'Mentor Six','mentor6@example.com', NULL, NULL);
            $this->addUser(18, 'Mentor Seven','mentor7@example.com', NULL, NULL);
            $this->addUser(19, 'Mentor Eight','mentor8@example.com', NULL, NULL);
            $this->addUser(20, 'Mentor Nine','mentor9@example.com', NULL, NULL);
            $this->addUser(21, 'Mentor Ten','mentor10@example.com', NULL, NULL);
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
