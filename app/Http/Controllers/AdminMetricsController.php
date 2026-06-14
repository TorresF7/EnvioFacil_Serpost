<?php

namespace App\Http\Controllers;

use App\Services\AnalyticsAggregator;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class AdminMetricsController extends Controller
{
    public function index(Request $request): Response
    {
        [$desde, $hasta] = $this->rango($request);

        $agg = new AnalyticsAggregator($desde, $hasta);

        return Inertia::render('Admin/Metrics', [
            'rango' => ['desde' => $desde->toDateString(), 'hasta' => $hasta->toDateString()],
            'resumen' => $agg->resumen(),
            'embudo' => $agg->embudo(),
            'abandonoCampos' => $agg->abandonoPorCampo(),
            'errores' => $agg->erroresPorCampo(),
            'veredictos' => $agg->veredictos(),
            'topProductos' => $agg->topProductos(),
            'topDesconocidos' => $agg->topDesconocidos(),
            'accesibilidad' => $agg->accesibilidad(),
            'dispositivos' => $agg->dispositivos(),
        ]);
    }

    private function rango(Request $request): array
    {
        $desde = $this->parseFecha($request->query('desde')) ?? now()->subDays(29);
        $hasta = $this->parseFecha($request->query('hasta')) ?? now();

        if ($desde->gt($hasta)) {
            [$desde, $hasta] = [$hasta, $desde];
        }

        return [$desde->startOfDay(), $hasta->endOfDay()];
    }

    private function parseFecha(?string $valor): ?Carbon
    {
        if (! $valor || ! preg_match('/^\d{4}-\d{2}-\d{2}$/', $valor)) {
            return null;
        }

        try {
            return Carbon::createFromFormat('Y-m-d', $valor);
        } catch (\Throwable) {
            return null;
        }
    }
}
