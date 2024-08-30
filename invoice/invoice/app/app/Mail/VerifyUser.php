<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifyUser extends Mailable
{
    use Queueable, SerializesModels;
    private $subjectName;
    private $contentData;
    private $appName;

    /**
     * Create a new message instance.
     */
    public function __construct(array $contents)
    {
        $this->subjectName = $contents['subject'] ?? "Verify Email Id";
        $this->contentData = $contents;
        $this->appName = env('APP_NAME' , 'Invoicer');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "{$this->appName} - {$this->subjectName}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.verifyUser',
            with: $this->contentData
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
