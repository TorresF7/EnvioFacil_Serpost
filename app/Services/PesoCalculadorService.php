<?php

namespace App\Services;

class PesoCalculadorService
{
    private const DIVISOR_VOLUMETRICO = 6000;

    public function volumetrico(?int $largo, ?int $ancho, ?int $alto): float
    {
        if (! $largo || ! $ancho || ! $alto) {
            return 0.0;
        }

        return round(($largo * $ancho * $alto) / self::DIVISOR_VOLUMETRICO, 3);
    }

    public function facturable(float $pesoReal, ?int $largo, ?int $ancho, ?int $alto): float
    {
        return round(max($pesoReal, $this->volumetrico($largo, $ancho, $alto)), 3);
    }
}
