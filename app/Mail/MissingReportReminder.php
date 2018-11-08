<?php

namespace App\Mail;

use App\ExpenseClaim;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\User;
use App\Mentee;

class MissingReportReminder extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $mentor;
    public $mentee;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $mentor, Mentee $mentee)
    {
        $this->mentor = $mentor;
        $this->mentee = $mentee;
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
