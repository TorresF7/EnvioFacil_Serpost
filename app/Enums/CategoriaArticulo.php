<?php

namespace App\Enums;

enum CategoriaArticulo: string
{
    case PROHIBIDO = 'prohibido';
    case PELIGROSO = 'peligroso';
    case RESTRINGIDO = 'restringido';
    case PERMITIDO = 'permitido';

    public function label(): string
    {
        return match ($this) {
            self::PROHIBIDO => 'Prohibido',
            self::PELIGROSO => 'Peligroso',
            self::RESTRINGIDO => 'Restringido',
            self::PERMITIDO => 'Permitido',
        };
    }

    public function icono(): string
    {
        return match ($this) {
            self::PROHIBIDO => '🚫',
            self::PELIGROSO => '☣️',
            self::RESTRINGIDO => '⚠️',
            self::PERMITIDO => '✅',
        };
    }

    public function mensaje(): string
    {
        return match ($this) {
            self::PROHIBIDO => 'No puede enviarse bajo ninguna circunstancia.',
            self::PELIGROSO => 'Representa un riesgo y no se admite por vía postal.',
            self::RESTRINGIDO => 'Requiere autorización o certificado especial.',
            self::PERMITIDO => 'Puede enviarse sin restricciones especiales.',
        };
    }

    public function bloquea(): bool
    {
        return in_array($this, [self::PROHIBIDO, self::PELIGROSO], true);
    }
}
