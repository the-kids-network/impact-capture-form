<?php

namespace App\Domains\Calendar\Services;

use App\Domains\Calendar\Models\PlannedSession;
use App\Exceptions\DuplicateException;
use App\Exceptions\NotAuthorisedException;
use App\Exceptions\NotFoundException;
use App\Domains\Calendar\Emails\MissingReportForPlannedSession;
use App\Domains\Calendar\Emails\PlannedSessionChangedToManager;
use App\Mentee;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PlannedSessionService {
   
    public function getPlannedSessions() {
        return PlannedSession::canSee()->get();
    }

    public function getPlannedSessionsForTheLastXDays($daysAgo) {
        return PlannedSession::canSee()->where('date', '>=', Carbon::now()->subDays($daysAgo))
            ->where('date', '<=', Carbon::now())
            ->get();
    }

    public function getPlannedSession($id) {
        return PlannedSession::canSee()->find($id);
    }

    public function getNextPlannedSession() {
        return PlannedSession::canSee()
            ->whereDate('date', '>=', Carbon::now())
            ->orderBy('date', 'asc')
            ->first();
    }

    public function deletePlannedSession($id) {
        $plannedSession = PlannedSession::canSee()->find($id);

        if (!$plannedSession) throw new NotFoundException("Planned session not found");

        PlannedSession::destroy($plannedSession->id);
        
        $user = Auth::user();
        if ($user->isMentor() && isset($user->manager)) {
            Mail::to($user->manager)
                ->send(new PlannedSessionChangedToManager($user, $plannedSession, "delete"));
        } 
    }
 
    public function createPlannedSession($menteeId, $date, $location) {
        $dateParsed = Carbon::createFromFormat('d-m-Y', $date)->setTime(0,0,0);

        $previouslyPlannedSession = PlannedSession::canSee()
            ->whereDate('date', '=', $dateParsed)
            ->whereMenteeId($menteeId)
            ->first();

        if (isset($previouslyPlannedSession)) {
            throw new DuplicateException($previouslyPlannedSession->id, "Planned session already exists for date and mentee");
        }

        $plannedSession = new PlannedSession();
        $plannedSession->mentee_id = $menteeId;
        $plannedSession->date = $dateParsed;
        $plannedSession->location = $location;
        $plannedSession->save();
        return $plannedSession;
    }

    public function updatePlannedSession($id, $menteeId, $date, $location) {
        $dateParsed = Carbon::createFromFormat('d-m-Y', $date)->setTime(0,0,0);

        $plannedSession = PlannedSession::canSee()->find($id);

        if (!$plannedSession) throw new NotFoundException("Planned session not found");
        if (!Mentee::canSee()->find($menteeId)) throw new NotAuthorisedException("User not authorised to change mentee's planned sessions");

        $previouslyPlannedSession = PlannedSession::canSee()
            ->where('id', '!=', $id)
            ->whereDate('date', '=', $dateParsed )
            ->whereMenteeId($menteeId)
            ->first();
    
        if (isset($previouslyPlannedSession)) {
            throw new DuplicateException($previouslyPlannedSession->id, "Planned session already exists for date and mentee");
        }

        $plannedSession->mentee_id = $menteeId;
        $plannedSession->date = $dateParsed;
        $plannedSession->location = $location;
        $plannedSession->save();

        // Send email if changed by mentor and mentor has manager 
        $isDirty = !empty($plannedSession->getChanges());
        $user = Auth::user();
        if ($user->isMentor() && isset($user->manager) && $isDirty) {
            Mail::to($user->manager)
                ->send(new PlannedSessionChangedToManager($user, $plannedSession, "change"));
        }

        return $plannedSession;
    }

    public function requestReportReminderEmail($plannedSessionId) {
        $plannedSession = PlannedSession::whereId($plannedSessionId)->first();
        if (!$plannedSession) throw new NotFoundException("Planned session not found");
        // check mentee and mentor is active
        if (!isset($plannedSession->mentee) || !isset($plannedSession->mentee->mentor)) {
            Log::debug("Not sending as mentee or mentor is deactivated for planned session");
            return;
        }

        // Logging
        Log::debug("Attempting to send reminder email");
        Log::debug("Planned session ID: ".$plannedSession->id);
        Log::debug("Planned session date: ".$plannedSession->date);
        Log::debug("Last reminder email sent: ".$plannedSession->last_email_reminder);

        // Send reminder email
        if ($plannedSession->isReminderEmailNeeded()) {
            $plannedSession->last_email_reminder = Carbon::now();
            $plannedSession->save();
            $shouldCCManager = $plannedSession->hasBeenAtLeastTwoDaysSincePlannedSessionDate();
            Mail::to($plannedSession->mentee->mentor)->send(new MissingReportForPlannedSession($shouldCCManager, $plannedSession));
        }
    }

}
