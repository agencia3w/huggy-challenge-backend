<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailBirthdays extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($reader, $books, $pages)
    {
        $this->reader = $reader;
        $this->books = $books;
        $this->pages = $pages;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.birthdays')
            ->subject("Feliz Aniversário")
            ->with([
                'reader' => $this->reader,
                'books' => $this->books,
                'pages' => $this->pages
            ]);
    }
}
