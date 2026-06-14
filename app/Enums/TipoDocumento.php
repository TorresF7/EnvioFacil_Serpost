<?php

namespace App\Enums;

enum TipoDocumento: string
{
    case DNI = 'dni';
    case PASAPORTE = 'pasaporte';
    case CARNET_EXTRANJERIA = 'ce';
    case CIP = 'cip';

    public function label(): string
    {
        return match ($this) {
            self::DNI => 'DNI',
            self::PASAPORTE => 'Pasaporte',
            self::CARNET_EXTRANJERIA => 'Carnet de Extranjería',
            self::CIP => 'CIP',
        };
    }

    public function longitudExacta(): ?int
    {
        return match ($this) {
            self::DNI => 8,
            default => null,
        };
    }
}
