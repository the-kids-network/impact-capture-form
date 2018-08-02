<?php

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
        // One Admin
        $this->addUser(1, 'Admin Only','admin@admin.com', 'admin', NULL);
        
        // Five Managers
        $this->addUser(2, 'Manager One','manager@one.com', 'manager', NULL);
        $this->addUser(3, 'Manager Two','manager@two.com', 'manager', NULL);
        $this->addUser(4, 'Manager Three','manager@three.com', 'manager', NULL);
        $this->addUser(5, 'Manager Four','manager@four.com', 'manager', NULL);
        $this->addUser(6, 'Manager Five','manager@five.com', 'manager', NULL);

        // Five Finance
        $this->addUser(7, 'Finance One','finance@one.com', 'finance', NULL);
        $this->addUser(8, 'Finance Two','finance@two.com', 'finance', NULL);
        $this->addUser(9, 'Finance Three','finance@three.com', 'finance', NULL);
        $this->addUser(10, 'Finance Four','finance@four.com', 'finance', NULL);
        $this->addUser(11, 'Finance Five','finance@five.com', 'finance', NULL);

        // Ten Mentors
        $this->addUser(12, 'Mentor One','mentor@one.com', NULL, NULL);
        $this->addUser(13, 'Mentor Two','mentor@two.com', NULL, NULL);
        $this->addUser(14, 'Mentor Three','mentor@three.com', NULL, NULL);
        $this->addUser(15, 'Mentor Four','mentor@four.com', NULL, NULL);
        $this->addUser(16, 'Mentor Five','mentor@five.com', NULL, NULL);
        $this->addUser(17, 'Mentor Six','mentor@six.com', NULL, NULL);
        $this->addUser(18, 'Mentor Seven','mentor@seven.com', NULL, NULL);
        $this->addUser(19, 'Mentor Eight','mentor@eight.com', NULL, NULL);
        $this->addUser(20, 'Mentor Nine','mentor@nine.com', NULL, NULL);
        $this->addUser(21, 'Mentor Ten','mentor@ten.com', NULL, NULL);
        
    }

    private function addUser($id, $name, $email, $role, $manager_id){
        DB::table('users')->insert([
            'id' => $id,
            'name' => $name,
            'email' => $email,
            'password' => bcrypt('secret'),
            'trial_ends_at' => '2018-02-21 00:25:39',
            'last_read_announcements_at' => '2018-02-21 00:25:39',
            'created_at' => '2018-02-21 00:25:39',
            'updated_at' => '2018-02-21 00:25:39',
            'role' => $role,
            'manager_id' => $manager_id
        ]);
    }

}
