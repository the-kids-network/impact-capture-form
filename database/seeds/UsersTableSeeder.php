<?php

use App\Mentee;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder {
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $this->command->info('Creating sample users...');

        // Admin
        $this->addAdmin('admin');

        // Managers
        $this->addManager('manager-1');
        $this->addManager('manager-2');
        $this->addManager('manager-3');

        // Mentors
        $this->addMentor('mentor-1', 'manager-1');
        $this->addMentor('mentor-2', 'manager-2');
        $this->addMentor('mentor-3', 'manager-3');
        $this->addMentor('mentor-4', 'manager-3');

        // Mentees
        $this->addMentee('mentee-1', 'mentor-1');
        $this->addMentee('mentee-2', 'mentor-2');
        $this->addMentee('mentee-3', 'mentor-3');
        $this->addMentee('mentee-4', 'mentor-4');
    }

    private function addAdmin($name) {
        // create manager
        $user = new User();
        $user->name = $name;
        $user->email = $this->emailFor($name);
        $user->role = 'admin';
        $user->password = bcrypt('secret');
        $user->save();
    }

    private function addManager($name) {
        // create manager
        $user = new User();
        $user->name = $name;
        $user->email = $this->emailFor($name);
        $user->role = 'manager';
        $user->password = bcrypt('secret');
        $user->save();
    }

    private function addMentor($name, $managerName) {
        // find manager
        $manager = User::whereName($managerName)->first();
        
        // create mentor
        $user = new User();
        $user->name = $name;
        $user->email = $this->emailFor($name);
        $user->manager_id = $manager->id;
        $user->password = bcrypt('secret');
        $user->save();
    }

    private function addMentee($name, $mentorName){
        $mentor = User::whereName($mentorName)->first();

        // Create mentee
        $mentee = new Mentee();
        $mentee->first_name = explode('-', $name)[0];
        $mentee->last_name = explode('-', $name)[1];
        $mentee->mentor_id = $mentor->id;
        $mentee->save();
    }

    private function emailFor($name) {
        if ( config('mail.test') == 'true' ) {
            $mailbox = config('mail.testMailbox');
            $email_parts = explode('@', $mailbox);
            return $email_parts[0].'+'.$$name.'@'.$email_parts[1];
        } else {
            return $name.'@example.com';
        }
    }
}
