<?php

namespace App\Mail;

use App\ExpenseClaim;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\User;
use App\Mentee;
use App\PlannedSession;

class MissingReportReminder extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $isReportLate;
    public $plannedSession;
    public $mentee;
    public $mentor;
    public $manager;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($isLate, PlannedSession $plannedSession) {
        $this->isReportLate = $isLate;
        $this->plannedSession = $plannedSession;
        $this->mentee = $this->plannedSession->mentee();
        $this->mentor = $this->mentee->mentor;
        $this->manager = $this->mentor->manager;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {

        $mail = $this
            ->to($this->mentor)
            ->subject('Report Submission Reminder')
            ->markdown('emails.report.missing_report_reminder');

        if (isset($this->manager)){
            $mail->replyTo($this->manager);
            if ($this->isReportLate) {
                $mail->cc($this->manager);
            }
        }

        return $mail;
    }
}
