<?php

namespace App\Enums;

enum RolUsuario: string
{
    case CLIENTE = 'cliente';
    case VENTANILLA = 'ventanilla';
    case ADMIN = 'admin';

    public function label(): string
    {
        return match ($this) {
            self::CLIENTE => 'Cliente',
            self::VENTANILLA => 'Ventanilla',
            self::ADMIN => 'Administrador',
        };
    }

    public function esStaff(): bool
    {
        return in_array($this, [self::VENTANILLA, self::ADMIN], true);
    }

    public function rutaInicio(): string
    {
        return $this->esStaff() ? '/panel' : '/app';
    }
}
