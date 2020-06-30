<?php

namespace App\Domains\Expenses\Mail;

use App\Domains\Expenses\Models\ExpenseClaim;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ClaimProcessedToMentor extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $claim;

    public $sessionMentee;

    public $sessionDate;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ExpenseClaim $claim, $sessionMentee, $sessionDate)
    {
        $this->claim = $claim;
        $this->sessionMentee = $sessionMentee;
        $this->sessionDate = $sessionDate;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail = $this
            ->subject('Expense Claim Processed')
            ->markdown('emails.claim.processed_to_mentor');

        if (isset($this->claim->mentor->manager)){
            $mail->replyTo($this->claim->mentor->manager);
        }

        return $mail;
    }
}
