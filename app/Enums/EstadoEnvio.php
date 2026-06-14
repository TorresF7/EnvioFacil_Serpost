<?php

namespace App\Enums;

enum EstadoEnvio: string
{
    case PREADMISION = 'preadmision';
    case PENDIENTE_DEPOSITO = 'pendiente_deposito';
    case ADMITIDO = 'admitido';
    case CLASIFICACION = 'clasificacion';
    case TRANSITO = 'transito';
    case ADUANA = 'aduana';
    case DISTRIBUCION = 'distribucion';
    case ENTREGADO = 'entregado';

    public function label(): string
    {
        return match ($this) {
            self::PREADMISION => 'Pre-admisión digital',
            self::PENDIENTE_DEPOSITO => 'Pendiente de depósito',
            self::ADMITIDO => 'Admitido en oficina',
            self::CLASIFICACION => 'En centro de clasificación',
            self::TRANSITO => 'En tránsito internacional',
            self::ADUANA => 'En aduana de destino',
            self::DISTRIBUCION => 'En distribución',
            self::ENTREGADO => 'Entregado',
        };
    }

    public function descripcion(): string
    {
        return match ($this) {
            self::PREADMISION => 'Tu información fue registrada digitalmente.',
            self::PENDIENTE_DEPOSITO => 'Lleva tu paquete a la oficina dentro de 48 horas.',
            self::ADMITIDO => 'El personal admitió tu envío en ventanilla.',
            self::CLASIFICACION => 'Pasó por el CCPL (rayos X y clasificación).',
            self::TRANSITO => 'Tu envío viaja hacia el país de destino.',
            self::ADUANA => 'En revisión por la aduana del país destino.',
            self::DISTRIBUCION => 'El operador local lo lleva al destinatario.',
            self::ENTREGADO => 'Tu envío llegó a su destino.',
        };
    }

    public function orden(): int
    {
        return match ($this) {
            self::PREADMISION => 0,
            self::PENDIENTE_DEPOSITO => 1,
            self::ADMITIDO => 2,
            self::CLASIFICACION => 3,
            self::TRANSITO => 4,
            self::ADUANA => 5,
            self::DISTRIBUCION => 6,
            self::ENTREGADO => 7,
        };
    }

    public static function timeline(): array
    {
        return array_map(fn (self $e) => [
            'valor' => $e->value,
            'label' => $e->label(),
            'descripcion' => $e->descripcion(),
            'orden' => $e->orden(),
        ], self::cases());
    }
}
