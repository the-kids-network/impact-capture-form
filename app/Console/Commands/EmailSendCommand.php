<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

use App\User;
use App\Mentee;
use App\Schedule;
use App\Report;
use Illuminate\Support\Facades\Log;

use App\Mail\MissingReportReminder;

use Illuminate\Support\Carbon;

class EmailSendCommand extends Command
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
        $schedules = $this->getSchedulesWithSessionDateInLastWeek();
        $schedules = $this->filterSchedulesThatDoNotHaveReport($schedules);
        $schedules = $this->filterSchedulesWhereEmailNotAlreadySentToday($schedules);

        foreach ($schedules as $schedule) {
            // assume session finished by 8pm latest
            $schedule_session_date = $schedule->next_session_date->setTime(20,00);

            $diff = $schedule_session_date->diff($this->now());

            Log::info("Schedule ID: ".$schedule->id);
            Log::info("Scheduled session date: ".$schedule_session_date);
            Log::info("Last reminder email sent: ".$schedule->last_email_reminder);
            Log::info("Days since scheduled session date and now: ".$diff->days);

            if ($diff->days == 2) {
                $this->sendEmail($schedule, false);
            } else if ($diff->days == 3 || $diff->days == 5) {
                $this->sendEmail($schedule, true);
            }
        }
    }

    private function getSchedulesWithSessionDateInLastWeek() {
        return Schedule::where('next_session_date', '>=', $this->daysAgo(7))
            ->where('next_session_date', '<=', $this->now())
            ->get();
    }

    private function filterSchedulesThatDoNotHaveReport($schedules) {
        return $schedules->filter(function($schedule, $key) {
            return $this->getReportForSchedule($schedule) == null;
        });
    }

    private function filterSchedulesWhereEmailNotAlreadySentToday($schedules) {
        return $schedules->filter(function($schedule, $key) {
            if(!isset($schedule->last_email_reminder)) return true;

            $diff = $schedule->last_email_reminder->diff($this->now());
            return $diff->days != 0;
        });
    }

    private function getReportForSchedule($schedule) {
        return Report::where('mentee_id', $schedule->mentee_id)
        ->where('session_date', $schedule->next_session_date)
        ->first();
    }

    private function sendEmail($schedule, $isLate) {
        Log::info("Sending reminder email");
        $schedule->last_email_reminder = $this->now();
        $schedule->save();
        $mentee = $schedule->mentee();

        $mail = Mail::to($mentee->mentor);
        if ($isLate) {
            $mail->cc($mentee->mentor->manager);
        }
        $mail->send(new MissingReportReminder($mentee->mentor, $mentee, $schedule));
    }

    private function daysAgo($days) {
        return $this->now()->subDays($days);
    }

    private function now() {
        return Carbon::now();
    }
}
