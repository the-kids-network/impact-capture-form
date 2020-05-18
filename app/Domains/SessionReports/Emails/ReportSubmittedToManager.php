<?php

namespace App\Domains\SessionReports\Emails;

use App\Domains\SessionReports\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReportSubmittedToManager extends Mailable implements ShouldQueue
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
        return $this
            ->subject('Report Received from Mentor')
            ->markdown('emails.report.submitted_to_manager');
    }
}
