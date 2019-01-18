<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;

class LogMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $reply;
    public $user;
    
    public function __construct(User $from, User $to)
    {
        $this->reply = $from;
        $this->user = $to;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user = $this->user;
        $reply = $this->reply;
        return $this->view('mail.demo')
                    ->with(compact(['reply', 'user']));
    }
}
