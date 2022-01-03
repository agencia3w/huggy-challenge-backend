<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->data['subject'];
        $message = $this->data['message'];
        $type = $this->data['type'];

        return $this->view('mails.notification')
            // ->from($address)
            ->subject($subject)
            ->with([
                'content' => $message,
                'subject' => $subject,
                'type' => $type
            ]);
    }
}
