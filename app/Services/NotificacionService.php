<?php

namespace App\Services;

use App\Mail\BienvenidaRegistro;
use App\Mail\EstadoActualizado;
use App\Models\Envio;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificacionService
{

    public function bienvenida(User $usuario): void
    {
        try {
            Mail::to($usuario->email)->send(new BienvenidaRegistro($usuario));
            Log::info('Correo de bienvenida enviado', ['email' => $usuario->email]);
        } catch (\Throwable $e) {
            Log::error('No se pudo enviar el correo de bienvenida', [
                'email' => $usuario->email,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function activar(Envio $envio, ?string $email, ?string $whatsapp): void
    {
        $envio->update([
            'notif_email' => $email,
            'notif_whatsapp' => $whatsapp,
        ]);

        Log::info('Notificaciones activadas', [
            'codigo' => $envio->codigo,
            'email' => $email,
            'whatsapp' => $whatsapp,
        ]);
    }

    public function notificarEstado(Envio $envio): void
    {
        $destino = $envio->notif_email ?: $envio->user?->email;

        if (! $destino) {
            Log::info('Notificación de estado sin destinatario', [
                'codigo' => $envio->codigo,
                'estado' => $envio->estado->value,
            ]);

            return;
        }

        try {
            Mail::to($destino)->send(new EstadoActualizado($envio));
            Log::info('Notificación de estado enviada', [
                'codigo' => $envio->codigo,
                'estado' => $envio->estado->value,
                'email' => $destino,
            ]);
        } catch (\Throwable $e) {
            Log::error('No se pudo enviar la notificación de estado', [
                'codigo' => $envio->codigo,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
