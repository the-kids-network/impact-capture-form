<?php

namespace App\Mail;

use App\ExpenseClaim;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\User;
use App\Mentee;
use App\Schedule;

class MissingReportReminder extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $mentor;
    public $mentee;
    public $schedule;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $mentor, Mentee $mentee, Schedule $schedule)
    {
        $this->mentor = $mentor;
        $this->mentee = $mentee;
        $this->schedule = $schedule;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Report Submission Reminder')
            ->markdown('emails.report.missing_report_reminder');
    }
}
