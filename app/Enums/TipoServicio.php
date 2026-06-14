<?php

namespace App\Enums;

enum TipoServicio: string
{
    case PEQUENO_PAQUETE = 'pp';
    case EMS = 'ems';
    case ENCOMIENDA = 'encomienda';

    public function formulario(): string
    {
        return match ($this) {
            self::PEQUENO_PAQUETE => 'CN22',
            self::EMS => 'CN23 EMS',
            self::ENCOMIENDA => 'CP72',
        };
    }

    public function pesoMaximo(): float
    {
        return match ($this) {
            self::PEQUENO_PAQUETE => 2.0,
            self::EMS => 30.0,
            self::ENCOMIENDA => 31.5,
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::PEQUENO_PAQUETE => 'Pequeño Paquete',
            self::EMS => 'EMS Express',
            self::ENCOMIENDA => 'Encomienda',
        };
    }

    public function descripcion(): string
    {
        return match ($this) {
            self::PEQUENO_PAQUETE => 'Hasta 2 kg. El más económico para envíos pequeños.',
            self::EMS => 'Hasta 30 kg. El más rápido, con seguimiento detallado.',
            self::ENCOMIENDA => 'Hasta 31.5 kg. Ideal para paquetes grandes.',
        };
    }

    public function tiempoEntrega(): string
    {
        return match ($this) {
            self::PEQUENO_PAQUETE => '15 a 30 días',
            self::EMS => '5 a 10 días',
            self::ENCOMIENDA => '20 a 45 días',
        };
    }
}
