<?php

namespace App\Services;

use App\Data\PartidasArancelarias;

class ArancelService
{

    public function sugerir(string $descripcion, int $limite = 5): array
    {
        $texto = $this->normalizar($descripcion);
        if (mb_strlen($texto) < 2) {
            return [];
        }

        $tokens = array_filter(explode(' ', $texto), fn ($t) => mb_strlen($t) >= 3);

        $resultados = [];
        foreach (PartidasArancelarias::todas() as $partida) {
            $score = $this->puntuar($texto, $tokens, $partida);
            if ($score > 0) {
                $resultados[] = [
                    'codigo' => $partida['codigo'],
                    'descripcion' => $partida['descripcion'],
                    'score' => round($score, 2),
                ];
            }
        }

        usort($resultados, fn ($a, $b) => $b['score'] <=> $a['score']);

        return array_slice($resultados, 0, $limite);
    }

    private function puntuar(string $texto, array $tokens, array $partida): float
    {
        $score = 0.0;

        foreach ($partida['keywords'] as $keyword) {
            $kw = $this->normalizar($keyword);
            if (str_contains($texto, $kw)) {

                $score += 3 + (str_contains($kw, ' ') ? 1 : 0);
            }
        }

        $descPartida = $this->normalizar($partida['descripcion']);
        foreach ($tokens as $token) {
            if (str_contains($descPartida, $token)) {
                $score += 0.5;
            }
        }

        return $score;
    }

    private function normalizar(string $texto): string
    {
        $texto = mb_strtolower(trim($texto));

        return strtr($texto, [
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ü' => 'u', 'ñ' => 'n',
        ]);
    }
}
