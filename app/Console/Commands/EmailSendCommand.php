<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

use App\User;
use App\Mentee;
use App\Schedule;
use App\Report;

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
    protected $description = 'Sends reminder emails to all relevant mentors';

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
    public function handle()
    {
        foreach (Schedule::where('next_session_date', '<', $this->daysAgo(4))->get() as $schedule) {
            $report = Report::where('mentee_id', $schedule->mentee_id)
                ->where('session_date', $schedule->next_session_date)
                ->first();

            // Only send reminder report if they've not been emailed about that mentee recently
            if (!$report && (!$schedule->last_email_reminder || $schedule->last_email_reminder < $this->daysAgo(7))) {
                $mentee = $schedule->mentee();

                $schedule->last_email_reminder = Carbon::now('Europe/London');
                $schedule->save();

                Mail::to($mentee->mentor)
                    ->send(new MissingReportReminder($mentee->mentor, $mentee, $schedule));
            }
        }
    }

    private function daysAgo($days) {
        // Add an extra hour to allow for slight changes in time triggered
        return Carbon::now()->subDays($days)->addHour();
    }
}
