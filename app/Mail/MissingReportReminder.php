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

    public $isReportLate;
    public $schedule;
    public $mentee;
    public $mentor;
    public $manager;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($isLate, Schedule $schedule) {
        $this->isReportLate = $isLate;
        $this->schedule = $schedule;
        $this->mentee = $this->schedule->mentee();
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
