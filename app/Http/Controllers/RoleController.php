<?php

namespace App\Http\Controllers;

use App\Mentee;
use App\User;
use Illuminate\Http\Request;

class RoleController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('dev');
    }

    public function index(){
        return view('roles.index');
    }

    public function mentor(){
        return view('roles.mentor')
            ->with('users',User::all())
            ->with('mentees',Mentee::all());
    }

    public function manager(){
        return view('roles.manager')
            ->with('users', User::all() );
    }
    public function finance(){
        return view('roles.finance')
            ->with('users', User::all() );
    }
    public function admin(){
        return view('roles.admin')
            ->with('users', User::all() );
    }

    public function store_manager(Request $request){
        $user = User::find($request->user_id);
        $user->role = 'manager' ;
        $user->save();
        return redirect('/roles/manager')->with('status','User promoted to Manager');
    }

    public function store_finance(Request $request){
        $user = User::find($request->user_id);
        $user->role = 'finance';
        $user->save();
        return redirect('/roles/finance')->with('status','User promoted to Finance');
    }
    
    public function store_admin(Request $request){
        $user = User::find($request->user_id);
        $user->role = 'admin' ;
        $user->save();
        return redirect('/roles/admin')->with('status','User promoted to Admin');
    }

    public function assignMentor(Request $request){
        $mentee = Mentee::find($request->mentee_id);
        $mentor = User::find($request->mentor_id);

        $mentee->mentor_id = $request->mentor_id;
        $mentee->save();

        return redirect('/roles/mentor')->with('status', $mentor->name . ' has been assigned to ' . $mentee->first_name);
    }

    public function assignManager(Request $request){
        $manager = User::find($request->manager_id);
        $mentor = User::find($request->mentor_id);
        $mentor->manager()->associate($manager);
        $mentor->save();
        return redirect('/roles/manager')->with('status', $manager->name . ' assigned to ' . $mentor->name );
    }

    public function delete_mentor(Request $request){
        $mentee = Mentee::find($request->mentee_id);
        $mentee->mentor_id = NULL;
        $mentee->save();

        return redirect('roles/mentor')->with('status', 'Mentor disassociated from Mentee');
    }

    public function delete_manager(Request $request){
        $user = User::find($request->manager_id);

        foreach($user->assignedMentors as $mentor){
            $mentor->manager_id = NULL;
            $mentor->save();
        }

        $user->role = NULL;
        $user->save();
        return redirect('/roles/manager')->with('status', $user->name . ' is no longer a manager.');
    }

    public function delete_finance(Request $request){
        $user = User::find($request->finance_id);
        $user->role = NULL;
        $user->save();
        return redirect('/roles/finance')->with('status',$user->name . ' is no longer in finance.');
    }

    public function delete_admin(Request $request){
        $user = User::find($request->admin_id);
        $user->role = NULL;
        $user->save();
        return redirect('/roles/admin')->with('status',$user->name . ' is no longer an admin.');
    }


}