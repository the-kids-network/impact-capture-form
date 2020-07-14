<?php

namespace App\Domains\Expenses\Mail;

use App\Domains\Expenses\Models\ExpenseClaim;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ClaimSubmittedToMentor extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $claim;

    public $session;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ExpenseClaim $claim, $session)
    {
        $this->claim = $claim;
        $this->session = $session;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail = $this
            ->subject('Expense Claim Received')
            ->markdown('emails.claim.submitted_to_mentor');

        if (isset($this->claim->mentor->manager)){
            $mail->replyTo($this->claim->mentor->manager);
        }

        return $mail;
    }
}
