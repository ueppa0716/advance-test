<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $content; // 変数名を変更

    /**
     * Create a new message instance.
     *
     * @param string $subject
     * @param string $content
     * @return void
     */
    public function __construct($subject, $content) // 変数名を変更
    {
        $this->subject = $subject;
        $this->content = $content; // 変数名を変更
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.notification')
            ->subject($this->subject)  // 件名を設定
            ->with(['content' => $this->content]); // メール本文をビューに渡す
    }
}
