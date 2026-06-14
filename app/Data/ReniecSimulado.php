<?php

namespace App\Data;

final class ReniecSimulado
{

    public const FIJOS = [
        '12345678' => 'María Pérez García',
        '87654321' => 'Eduardo Cordero Villanueva',
        '10203040' => 'José Escudra Canales',
    ];

    private const NOMBRES = [
        'María', 'José', 'Luis', 'Carmen', 'Rosa', 'Carlos', 'Ana', 'Jorge',
        'Lucía', 'Miguel', 'Elena', 'Pedro', 'Sofía', 'Manuel', 'Julia', 'Andrés',
    ];

    private const APELLIDOS = [
        'García', 'Rodríguez', 'Flores', 'Quispe', 'Mamani', 'Torres', 'Vásquez',
        'Rojas', 'Castro', 'Sánchez', 'Ramírez', 'Huamán', 'Díaz', 'Cruz', 'Vega', 'Salazar',
    ];

    public static function consultar(string $dni): ?string
    {
        if (! preg_match('/^\d{8}$/', $dni)) {
            return null;
        }

        if (isset(self::FIJOS[$dni])) {
            return self::FIJOS[$dni];
        }

        $d = array_map('intval', str_split($dni));
        $nombre = self::NOMBRES[($d[0] * 10 + $d[1]) % count(self::NOMBRES)];
        $apPaterno = self::APELLIDOS[($d[2] * 10 + $d[3]) % count(self::APELLIDOS)];
        $apMaterno = self::APELLIDOS[($d[4] * 10 + $d[5]) % count(self::APELLIDOS)];

        return "{$nombre} {$apPaterno} {$apMaterno}";
    }
}
