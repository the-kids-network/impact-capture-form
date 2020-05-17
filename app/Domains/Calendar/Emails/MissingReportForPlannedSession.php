<?php

namespace App\Domains\Calendar\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Domains\Calendar\Models\PlannedSession;

class MissingReportForPlannedSession extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $ccManager;
    public $plannedSession;
    public $mentee;
    public $mentor;
    public $manager;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($ccManager, PlannedSession $plannedSession) {
        $this->ccManager = $ccManager;
        $this->plannedSession = $plannedSession;
        $this->mentee = $this->plannedSession->mentee;
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
            ->subject('Report Submission Reminder')
            ->markdown('emails.report.missing_report_reminder');

        if (isset($this->manager)){
            $mail->replyTo($this->manager);
            if ($this->ccManager) {
                $mail->cc($this->manager);
            }
        }

        return $mail;
    }
}
