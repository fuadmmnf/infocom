<?php

namespace App\Mail;

use App\Models\Complain;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerComplainApproval extends Mailable {
    use Queueable, SerializesModels;

    public $complain;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Complain $complain) {
        $this->complain = $complain;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        return $this->subject("Infocom Complain Feedback")
            ->view('mail.complainapproval');
    }
}
