<?php

namespace App\Mail;

use App\Models\CustomerMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerCustomMessage extends Mailable implements ShouldQueue {
    use Queueable, SerializesModels;

    public $customermessage;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(CustomerMessage $customerMessage) {
        $this->customermessage = $customerMessage;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        return $this->subject("Infocom Notice")
            ->view('mail.custommessage');
    }
}
