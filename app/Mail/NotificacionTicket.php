<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificacionTicket extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;
    public $messageText;

    /**
     * Create a new message instance.
     */
    public function __construct($ticket, $messageText = '')
    {
        $this->ticket = $ticket;
        $this->messageText = $messageText;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Notificación de Ticket: ' . $this->ticket->titulo,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.notificacion_ticket',
        );
    }
}
