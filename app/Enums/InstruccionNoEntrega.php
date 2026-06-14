<?php

namespace App\Enums;

enum InstruccionNoEntrega: string
{
    case DEVOLVER = 'devolver';
    case ABANDONAR = 'abandonar';
    case REDIRIGIR = 'redirigir';

    public function label(): string
    {
        return match ($this) {
            self::DEVOLVER => 'Devolver al remitente',
            self::ABANDONAR => 'Tratar como abandonado',
            self::REDIRIGIR => 'Redirigir a otra dirección',
        };
    }
}
