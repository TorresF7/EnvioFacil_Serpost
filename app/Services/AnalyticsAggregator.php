<?php

namespace App\Services;

use App\Models\AnalyticsEvent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsAggregator
{

    public const PASOS = [
        'producto' => 'Producto',
        'destino' => 'Destino',
        'contenido' => 'Contenido',
        'remitente' => 'Remitente',
        'servicio' => 'Servicio y pago',
        'revisa' => 'Revisa',
    ];

    public function __construct(
        private Carbon $desde,
        private Carbon $hasta,
    ) {}

    private function base(): Builder
    {
        return AnalyticsEvent::query()->whereBetween('occurred_at', [$this->desde, $this->hasta]);
    }

    public function resumen(): array
    {
        $sesiones = (int) $this->base()->distinct()->count('session_id');
        $completadas = (int) $this->base()->where('type', 'completion')->distinct()->count('session_id');

        $duraciones = $this->base()
            ->where('type', 'completion')
            ->whereNotNull('duration_ms')
            ->pluck('duration_ms')
            ->all();

        $medianaMs = $this->mediana($duraciones);

        $dispositivos = $this->dispositivos();
        $totalDisp = max(1, $dispositivos['mobile'] + $dispositivos['desktop']);

        return [
            'sesiones' => $sesiones,
            'completadas' => $completadas,
            'tasa_completitud' => $sesiones > 0 ? round($completadas / $sesiones * 100) : 0,
            'tiempo_mediano_ms' => $medianaMs,
            'tiempo_mediano_min' => $medianaMs ? round($medianaMs / 60000, 1) : null,
            'dispositivos' => $dispositivos,
            'pct_movil' => round($dispositivos['mobile'] / $totalDisp * 100),
        ];
    }

    public function embudo(): array
    {
        $entraron = $this->distinctPorPaso('step_enter');
        $completaron = $this->distinctPorPaso('step_complete');

        $filas = [];
        foreach (self::PASOS as $clave => $label) {
            $e = (int) ($entraron[$clave] ?? 0);
            $c = (int) ($completaron[$clave] ?? 0);
            $abandono = max(0, $e - $c);
            $filas[] = [
                'step' => $clave,
                'label' => $label,
                'entraron' => $e,
                'completaron' => $c,
                'abandonaron' => $abandono,
                'caida_pct' => $e > 0 ? round($abandono / $e * 100) : 0,
            ];
        }

        return $filas;
    }

    private function distinctPorPaso(string $type): array
    {
        return $this->base()
            ->where('type', $type)
            ->whereNotNull('step_id')
            ->select('step_id', DB::raw('COUNT(DISTINCT session_id) as n'))
            ->groupBy('step_id')
            ->pluck('n', 'step_id')
            ->all();
    }

    public function abandonoPorCampo(int $limite = 12): array
    {
        return $this->base()
            ->where('type', 'step_abandon')
            ->whereNotNull('field_id')
            ->select('field_id', 'step_id', DB::raw('COUNT(*) as total'))
            ->groupBy('field_id', 'step_id')
            ->orderByDesc('total')
            ->limit($limite)
            ->get()
            ->map(fn ($r) => [
                'field_id' => $r->field_id,
                'step_id' => $r->step_id,
                'total' => (int) $r->total,
            ])
            ->all();
    }

    public function erroresPorCampo(int $limite = 12): array
    {
        return $this->base()
            ->where('type', 'field_error')
            ->whereNotNull('field_id')
            ->select('field_id', 'error_code', DB::raw('COUNT(*) as total'))
            ->groupBy('field_id', 'error_code')
            ->orderByDesc('total')
            ->limit($limite)
            ->get()
            ->map(fn ($r) => [
                'field_id' => $r->field_id,
                'error_code' => $r->error_code ?? 'desconocido',
                'total' => (int) $r->total,
            ])
            ->all();
    }

    public function veredictos(): array
    {
        $conteo = $this->base()
            ->where('type', 'verifier_query')
            ->whereNotNull('verdict')
            ->select('verdict', DB::raw('COUNT(*) as total'))
            ->groupBy('verdict')
            ->pluck('total', 'verdict')
            ->all();

        return [
            'verde' => (int) ($conteo['verde'] ?? 0),
            'ambar' => (int) ($conteo['ambar'] ?? 0),
            'rojo' => (int) ($conteo['rojo'] ?? 0),
            'desconocido' => (int) ($conteo['desconocido'] ?? 0),
        ];
    }

    public function topProductos(int $limite = 10): array
    {
        return $this->topTerminos(false, $limite);
    }

    public function topDesconocidos(int $limite = 10): array
    {
        return $this->topTerminos(true, $limite);
    }

    private function topTerminos(bool $needsReview, int $limite): array
    {
        return $this->base()
            ->where('type', 'verifier_query')
            ->where('needs_review', $needsReview)
            ->whereNotNull('term')
            ->select('term', DB::raw('COUNT(*) as total'))
            ->groupBy('term')
            ->orderByDesc('total')
            ->limit($limite)
            ->get()
            ->map(fn ($r) => ['term' => $r->term, 'total' => (int) $r->total])
            ->all();
    }

    public function accesibilidad(): array
    {
        $features = $this->base()
            ->where('type', 'a11y_toggle')
            ->whereNotNull('feature')
            ->select('feature', DB::raw('COUNT(*) as total'))
            ->groupBy('feature')
            ->pluck('total', 'feature')
            ->all();

        $idiomas = $this->base()
            ->where('type', 'lang_change')
            ->whereNotNull('lang')
            ->select('lang', DB::raw('COUNT(*) as total'))
            ->groupBy('lang')
            ->pluck('total', 'lang')
            ->all();

        return [
            'features' => [
                'aplus' => (int) ($features['aplus'] ?? 0),
                'contrast' => (int) ($features['contrast'] ?? 0),
                'audio' => (int) ($features['audio'] ?? 0),
                'voice' => (int) ($features['voice'] ?? 0),
            ],
            'idiomas' => [
                'es' => (int) ($idiomas['es'] ?? 0),
                'qu' => (int) ($idiomas['qu'] ?? 0),
                'en' => (int) ($idiomas['en'] ?? 0),
            ],
        ];
    }

    public function dispositivos(): array
    {
        $conteo = $this->base()
            ->where('type', 'completion')
            ->whereNotNull('device')
            ->select('device', DB::raw('COUNT(*) as total'))
            ->groupBy('device')
            ->pluck('total', 'device')
            ->all();

        return [
            'mobile' => (int) ($conteo['mobile'] ?? 0),
            'desktop' => (int) ($conteo['desktop'] ?? 0),
        ];
    }

    private function mediana(array $valores): ?int
    {
        if ($valores === []) {
            return null;
        }
        sort($valores);
        $n = count($valores);
        $medio = intdiv($n, 2);

        return $n % 2
            ? (int) $valores[$medio]
            : (int) round(($valores[$medio - 1] + $valores[$medio]) / 2);
    }
}
