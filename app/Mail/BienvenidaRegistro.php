<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BienvenidaRegistro extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $usuario) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '¡Bienvenido a SERPOST Envío Fácil!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.bienvenida',
            with: ['usuario' => $this->usuario],
        );
    }
}
