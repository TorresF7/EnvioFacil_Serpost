<?php

namespace App\Services;

use App\Data\ArticulosRestringidos;
use App\Enums\CategoriaArticulo;

class VerificadorProductoService
{
    public function verificar(string $descripcion): CategoriaArticulo
    {
        $texto = $this->normalizar($descripcion);

        if ($this->coincide($texto, ArticulosRestringidos::CONTROLADOS)) {
            return CategoriaArticulo::PROHIBIDO;
        }

        if ($this->coincide($texto, ArticulosRestringidos::PROHIBIDOS)) {
            return CategoriaArticulo::PROHIBIDO;
        }

        if ($this->coincide($texto, ArticulosRestringidos::PELIGROSOS)) {
            return CategoriaArticulo::PELIGROSO;
        }

        if ($this->coincide($texto, ArticulosRestringidos::RESTRINGIDOS)) {
            return CategoriaArticulo::RESTRINGIDO;
        }

        return CategoriaArticulo::PERMITIDO;
    }

    public function verificarDetallado(string $descripcion): array
    {
        $texto = $this->normalizar($descripcion);

        $controlado = $this->primeraCoincidencia($texto, ArticulosRestringidos::CONTROLADOS);
        if ($controlado !== null) {
            return [
                'categoria' => CategoriaArticulo::PROHIBIDO,
                'palabra' => $controlado,
                'entidad' => 'DIGEMID',
                'documento' => ArticulosRestringidos::DOCUMENTO_POR_ENTIDAD['DIGEMID'] ?? null,
            ];
        }

        foreach ([
            CategoriaArticulo::PROHIBIDO->value => ArticulosRestringidos::PROHIBIDOS,
            CategoriaArticulo::PELIGROSO->value => ArticulosRestringidos::PELIGROSOS,
            CategoriaArticulo::RESTRINGIDO->value => ArticulosRestringidos::RESTRINGIDOS,
        ] as $categoria => $lista) {
            $palabra = $this->primeraCoincidencia($texto, $lista);

            if ($palabra !== null) {
                $entidad = ArticulosRestringidos::ENTIDAD_POR_PALABRA[$palabra] ?? null;

                return [
                    'categoria' => CategoriaArticulo::from($categoria),
                    'palabra' => $palabra,
                    'entidad' => $entidad,
                    'documento' => $entidad ? (ArticulosRestringidos::DOCUMENTO_POR_ENTIDAD[$entidad] ?? null) : null,
                ];
            }
        }

        return [
            'categoria' => CategoriaArticulo::PERMITIDO,
            'palabra' => null,
            'entidad' => null,
            'documento' => null,
        ];
    }

    private function normalizar(string $texto): string
    {
        $texto = mb_strtolower(trim($texto));
        $sinTildes = strtr($texto, [
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ü' => 'u',
        ]);

        return $sinTildes;
    }

    private function coincide(string $texto, array $lista): bool
    {
        return $this->primeraCoincidencia($texto, $lista) !== null;
    }

    private function primeraCoincidencia(string $texto, array $lista): ?string
    {
        foreach ($lista as $palabra) {
            if (str_contains($texto, $palabra)) {
                return $palabra;
            }
        }

        return null;
    }
}
