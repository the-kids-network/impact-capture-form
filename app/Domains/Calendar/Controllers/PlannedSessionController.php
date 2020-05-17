<?php

namespace App\Domains\Calendar\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Calendar\Services\PlannedSessionService;
use App\Exceptions\DuplicateException;
use App\Exceptions\NotAuthorisedException;
use App\Exceptions\NotFoundException;
use App\Mentee;
use Illuminate\Http\Request;

class PlannedSessionController extends Controller {

    private $plannedSessionService;

    private $validationRules = [
        'next_session_date' => 'required|date|date_format:d-m-Y',
        'next_session_location' => 'required|string|max:50'
    ];

    public function __construct(PlannedSessionService $plannedSessionService) {
        $this->plannedSessionService = $plannedSessionService;
        $this->middleware('auth');
        $this->middleware('hasAnyOfRoles:admin,manager,mentor');
    }

    public function newPlannedSession(Request $request) {
        return view('planned_session.new')
            ->with('mentees', Mentee::canSee()->get());
    }

    public function getOne($id) {
        $plannedSession = $this->plannedSessionService->getPlannedSession($id);
        if (!$plannedSession) {
            abort(401, 'Unauthorized'); 
        }

        return view('planned_session.show', compact('plannedSession'))
            ->with('plannedSession', $plannedSession);
    }

    public function getNext(Request $request) {
        $plannedSession = $this->plannedSessionService->getNextPlannedSession();

        if (! isset($plannedSession)) {
           return redirect('/planned-session/new')
                ->with('status', "You do not have planned session scheduled today or in the future. Create a new one instead below.");
        }

        return view('planned_session.show', compact('plannedSession'))
            ->with('plannedSession', $plannedSession);
    }

    public function delete($id) {
        try {
            $this->plannedSessionService->deletePlannedSession($id);
        } catch (NotFoundException $e) {
            abort(401,'Unauthorized');
        }

        return redirect('/calendar');
    }

    public function create(Request $request) {
        $request->validate($this->validationRules);    

        // Authorize
        if (! Mentee::canSee()->find($request->mentee_id)) {
            abort(401, 'Unauthorized'); 
        }
        
        // Create planned session
        try {
            $this->plannedSessionService->createPlannedSession(
                $request->mentee_id,
                $request->next_session_date,
                $request->next_session_location
            );
        } catch(DuplicateException $e) {
            return back()->withInput()
                ->withErrors('Looks like you already have a session planned for that date and mentee. 
                    <a href="/planned-session/'.$e->duplicateObjectId.'">Change it here.</a>');
        }

        return redirect('/calendar');
    }

    public function update(Request $request, $id) {
        $request->validate($this->validationRules);   

        try {
            $this->plannedSessionService->updatePlannedSession(
                $id,
                $request->mentee_id,
                $request->next_session_date,
                $request->next_session_location
            );
        } catch (NotFoundException | NotAuthorisedException $e) {
            abort(401, 'Unauthorized');

        } catch(DuplicateException $e) {
            return back()->withInput()
                ->withErrors('Looks like you already have a session planned for that date and mentee. 
                    <a href="/planned-session/'.$e->duplicateObjectId.'">Change it here.</a>');
        }

        return redirect('/calendar');
    }
}
