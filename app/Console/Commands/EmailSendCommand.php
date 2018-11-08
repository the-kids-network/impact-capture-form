<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

use App\User;
use App\Mentee;

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
        foreach (Mentee::where('last_email_reminder', '<', $this->daysAgo(6))->get() as $mentee) {

            $latest_report = $mentee->reports()->where('created_at', '>=', $this->daysAgo(7))->get();

            if ($latest_report->isEmpty()) {
                $mentee->last_email_reminder = Carbon::now('Europe/London');
                $mentee->save();

                Mail::to($mentee->mentor)->send(new MissingReportReminder($mentee->mentor, $mentee));
            }
        }
    }

    private function daysAgo($days) {
        return Carbon::now()->subDays($days);
    }
}
