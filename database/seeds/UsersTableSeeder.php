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
        $this->command->info('Creating sample users...' . config('app.env'));
        if ( config('app.env') != 'production') {
            // One Admin
            $this->addUser(1, 'Admin Only', 'admin', 'admin', NULL);

            // Five Managers
            $this->addUser(2, 'Manager One', 'manager1', 'manager', NULL);
            $this->addUser(3, 'Manager Two', 'manager2', 'manager', NULL);
            $this->addUser(4, 'Manager Three', 'manager3', 'manager', NULL);
            $this->addUser(5, 'Manager Four', 'manager4', 'manager', NULL);
            $this->addUser(6, 'Manager Five', 'manager5', 'manager', NULL);

            // Five Finance
            $this->addUser(7, 'Finance One', 'finance1', 'finance', NULL);
            $this->addUser(8, 'Finance Two', 'finance2', 'finance', NULL);
            $this->addUser(9, 'Finance Three', 'finance3', 'finance', NULL);
            $this->addUser(10, 'Finance Four', 'finance4', 'finance', NULL);
            $this->addUser(11, 'Finance Five', 'finance5', 'finance', NULL);

            // Ten Mentors
            $this->addUser(12, 'Mentor One', 'mentor1', NULL, 2);
            $this->addUser(13, 'Mentor Two', 'mentor2', NULL, 3);
            $this->addUser(14, 'Mentor Three', 'mentor3', NULL, 3);
            $this->addUser(15, 'Mentor Four', 'mentor4', NULL, NULL);
            $this->addUser(16, 'Mentor Five', 'mentor5', NULL, NULL);
            $this->addUser(17, 'Mentor Six', 'mentor6', NULL, NULL);
            $this->addUser(18, 'Mentor Seven', 'mentor7', NULL, NULL);
            $this->addUser(19, 'Mentor Eight', 'mentor8', NULL, NULL);
            $this->addUser(20, 'Mentor Nine', 'mentor9', NULL, NULL);
            $this->addUser(21, 'Mentor Ten', 'mentor10', NULL, NULL);
        }
    }

    private function addUser($id, $name, $email_prefix, $role, $manager_id) {
        if ( config('mail.test') == 'true' ) {
            $mailbox = config('mail.testMailbox');
            $email_parts = explode('@', $mailbox);
            $email_of_user = $email_parts[0].'+'.$email_prefix.'@'.$email_parts[1];
        } else {
            $email_of_user = $email_prefix.'@example.com';
        }

        User::firstOrCreate([
            'id' => $id
        ], [
            'name' => $name,
            'email' => $email_of_user,
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
