<?php

namespace App\Mail;

use App\ExpenseClaim;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ClaimProcessedToMentor extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $claim;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ExpenseClaim $claim)
    {
        $this->claim = $claim;
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
