<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Api_permanent;

class TestMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public string $type;
    public array $data;

    public function __construct(string $type, array $data)
    {
        $this->type = $type;
        $this->data = $data;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = match ($this->type) {
            'welcome' => 'Welcome to DriversDeck!',
            'trip_posted' => 'Your Trip Has Been Posted',
            default => 'DriversDeck Notification',
        };

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        $view = match ($this->type) {
            'welcome' => 'emails.welcome',
            'trip_posted' => 'emails.trip_posted',
            default => 'emails.default',
        };

        return new Content(
            view: $view,
            with: $this->data
        );
    }
    /**
     * Get the message content definition.
     */
    // public function content(): Content
    // {

    //     $dr =  new Api_permanent();

    //     $dr_det = DB::table('Corporate')->where('id', 1)->first();


    //     return new Content(
    //         view: 'mail',
    //         with: [
    //             'userName' =>  $dr_det->name,
    //             'message' => 'This is a test email from DriversDeck.',
    //         ]
    //     );
    // }

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
