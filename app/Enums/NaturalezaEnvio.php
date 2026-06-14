<?php

namespace App\Enums;

enum NaturalezaEnvio: string
{
    case REGALO = 'regalo';
    case VENTA = 'venta';
    case MUESTRA = 'muestra';
    case DOCUMENTOS = 'documentos';
    case DEVOLUCION = 'devolucion';
    case VARIADO = 'variado';
    case OTRO = 'otro';

    public function label(): string
    {
        return match ($this) {
            self::REGALO => 'Regalo',
            self::VENTA => 'Venta de bienes',
            self::MUESTRA => 'Muestra comercial',
            self::DOCUMENTOS => 'Documentos',
            self::DEVOLUCION => 'Devolución',
            self::VARIADO => 'Contenido variado',
            self::OTRO => 'Otro',
        };
    }

    public function requiereDocumento(): bool
    {
        return $this !== self::REGALO;
    }
}
