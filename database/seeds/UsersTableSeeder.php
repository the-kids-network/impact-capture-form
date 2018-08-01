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
        $this->addUser('Admin Only','admin@admin.com', NULL, NULL);
        
        // Five Managers
        $this->addUser('Manager One','manager@one.com', NULL, NULL);
        $this->addUser('Manager Two','manager@two.com', NULL, NULL);
        $this->addUser('Manager Three','manager@three.com', NULL, NULL);
        $this->addUser('Manager Four','manager@four.com', NULL, NULL);
        $this->addUser('Manager Five','manager@five.com', NULL, NULL);
                
        // Five Finance
        $this->addUser('Finance One','finance@one.com', NULL, NULL);
        $this->addUser('Finance Two','finance@two.com', NULL, NULL);
        $this->addUser('Finance Three','finance@three.com', NULL, NULL);
        $this->addUser('Finance Four','finance@four.com', NULL, NULL);
        $this->addUser('Finance Five','finance@five.com', NULL, NULL);
        
        // Ten Mentors
        $this->addUser('Mentor One','mentor@one.com', NULL, NULL);
        $this->addUser('Mentor Two','mentor@two.com', NULL, NULL);
        $this->addUser('Mentor Three','mentor@three.com', NULL, NULL);
        $this->addUser('Mentor Four','mentor@four.com', NULL, NULL);
        $this->addUser('Mentor Five','mentor@five.com', NULL, NULL);
        $this->addUser('Mentor Six','mentor@six.com', NULL, NULL);
        $this->addUser('Mentor Seven','mentor@seven.com', NULL, NULL);
        $this->addUser('Mentor Eight','mentor@eight.com', NULL, NULL);
        $this->addUser('Mentor Nine','mentor@nine.com', NULL, NULL);
        $this->addUser('Mentor Ten','mentor@ten.com', NULL, NULL);
        
    }

    private function addUser($name, $email, $role_id, $manager_id){
        DB::table('users')->insert([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt('secret'),
            'trial_ends_at' => '2018-02-21 00:25:39',
            'last_read_announcements_at' => '2018-02-21 00:25:39',
            'created_at' => '2018-02-21 00:25:39',
            'updated_at' => '2018-02-21 00:25:39',
            'role' => $role_id,
            'manager_id' => $manager_id
        ]);
    }

}
