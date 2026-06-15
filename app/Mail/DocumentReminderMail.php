<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DocumentReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $document;
    public $monthsText;
    public $expiredAt;

    public function __construct($document, $monthsText, $expiredAt)
    {
        $this->document = $document;
        $this->monthsText = $monthsText;
        $this->expiredAt = $expiredAt;
    }

    public function build()
    {
        return $this->subject('Document Reminder Mail')->view('email.reminder');
    }
}
