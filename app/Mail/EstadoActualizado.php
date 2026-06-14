<?php

namespace App\Mail;

use App\Models\Envio;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EstadoActualizado extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Envio $envio) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Tu envío {$this->envio->codigo}: {$this->envio->estado->label()}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.estado',
            with: ['envio' => $this->envio],
        );
    }
}
