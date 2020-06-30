<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Domains\SessionReports\Models\Report;
use App\Domains\Calendar\Services\PlannedSessionService;

class MissingReportReminderEmailSendCommand extends Command
{
    private $plannedSessionService;

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
    public function __construct(PlannedSessionService $plannedSessionService)
    {
        $this->plannedSessionService = $plannedSessionService;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $plannedSessions = $this->plannedSessionService->getPlannedSessionsForTheLastXDays(7);
        $plannedSessions = $this->filterPlannedSessionsThatDoNotHaveReport($plannedSessions);

        foreach ($plannedSessions as $plannedSession) {
           $this->plannedSessionService->requestReportReminderEmail($plannedSession->id);
        }
    }

    private function filterPlannedSessionsThatDoNotHaveReport($plannedSessions) {
        return $plannedSessions->filter(function($plannedSession, $key) {
            return $this->getReportForPlannedSession($plannedSession) == null;
        });
    }

    private function getReportForPlannedSession($plannedSession) {
        return Report::where('mentee_id', $plannedSession->mentee_id)
                     ->where('session_date', $plannedSession->date)
                     ->first();
    }

}
