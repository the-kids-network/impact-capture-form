<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

use App\User;
use App\Mentee;
use App\PlannedSession;
use App\Report;
use Illuminate\Support\Facades\Log;

use App\Mail\MissingReportReminder;

use Illuminate\Support\Carbon;

class MissingReportReminderEmailSendCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends reminder emails to all relevant mentors who need to submit session reports';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $plannedSessions = $this->getPlannedSessionsWithSessionDateInLastWeek();
        $plannedSessions = $this->filterPlannedSessionsThatDoNotHaveReport($plannedSessions);
        $plannedSessions = $this->filterPlannedSessionsWhereEmailNotAlreadySentToday($plannedSessions);

        foreach ($plannedSessions as $plannedSession) {
            // assume session finished by 8pm UTC latest
            $planned_session_date = $plannedSession->date->setTime(20,00);

            $diff = $planned_session_date->diff($this->now());

            Log::debug("Planned session ID: ".$plannedSession->id);
            Log::debug("Planned session date: ".$planned_session_date);
            Log::debug("Last reminder email sent: ".$plannedSession->last_email_reminder);
            Log::debug("Days since planned session date and now: ".$diff->days);

            if ($diff->days == 2) {
                $this->sendEmail($plannedSession, false);
            } else if ($diff->days == 3 || $diff->days == 5) {
                $this->sendEmail($plannedSession, true);
            } else {
                Log::debug("Reminder email not required yet");
            }
        }
    }

    private function getPlannedSessionsWithSessionDateInLastWeek() {
        return PlannedSession::where('date', '>=', $this->daysAgo(7))
            ->where('date', '<=', $this->now())
            ->get();
    }

    private function filterPlannedSessionsThatDoNotHaveReport($plannedSessions) {
        return $plannedSessions->filter(function($plannedSession, $key) {
            return $this->getReportForPlannedSession($plannedSession) == null;
        });
    }

    private function filterPlannedSessionsWhereEmailNotAlreadySentToday($plannedSessions) {
        return $plannedSessions->filter(function($plannedSession, $key) {
            if(!isset($plannedSession->last_email_reminder)) return true;

            $diff = $plannedSession->last_email_reminder->diff($this->now());
            return $diff->days != 0;
        });
    }

    private function getReportForPlannedSession($plannedSession) {
        return Report::where('mentee_id', $plannedSession->mentee_id)
        ->where('session_date', $plannedSession->date)
        ->first();
    }

    private function sendEmail($plannedSession, $isLate) {
        if (isset($plannedSession->mentee) && isset($plannedSession->mentee->mentor)) {
            Log::debug("Sending reminder email");
            $plannedSession->last_email_reminder = $this->now();
            $plannedSession->save();
            Mail::to($plannedSession->mentee->mentor)->send(
                new MissingReportReminder($isLate, $plannedSession)
            );
        } else {
            Log::debug("Not sending as mentee or mentor is deactivated");
        }
    }

    private function daysAgo($days) {
        return $this->now()->subDays($days);
    }

    private function now() {
        return Carbon::now();
    }
}
