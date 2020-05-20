<?php

namespace App\Domains\Calendar\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Domains\Calendar\Models\PlannedSession;

class PlannedSessionChangedToManager extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $mentor;
    public $plannedSession;
    public $typeOfChange;

    public function __construct($mentor, PlannedSession $plannedSession, $typeOfChange) {
        $this->mentor = $mentor;
        $this->plannedSession = $plannedSession;
        $this->typeOfChange = $typeOfChange;
    }

    public function build() {
        return $this
            ->subject('Planned Session Changed')
            ->markdown('emails.planned_session.changed_to_manager');
    }
}
