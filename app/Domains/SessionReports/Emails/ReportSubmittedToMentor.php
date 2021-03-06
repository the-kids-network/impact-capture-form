<?php

namespace App\Domains\SessionReports\Emails;

use App\Domains\SessionReports\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReportSubmittedToMentor extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $report;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Report $report)
    {
        $this->report = $report;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail = $this
            ->subject('Report Received')
            ->markdown('emails.report.submitted_to_mentor');

        if (isset($this->report->mentor->manager)){
            $mail->replyTo($this->report->mentor->manager);
        }

        return $mail;
    }
}
