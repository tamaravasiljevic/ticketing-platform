<?php

namespace App\Mail;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EventTimeChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $oldTime;
    public $newTime;

    public function __construct(Event $event, $oldTime, $newTime)
    {
        $this->event = $event;
        $this->oldTime = $oldTime;
        $this->newTime = $newTime;
    }

    public function build()
    {
            return $this->subject('Changed start time for event: ' . $this->event->title)
            ->view('emails.event_time_changed');
    }
}
