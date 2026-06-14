<?php

namespace App\Http\Controllers;

use App\Enums\EstadoEnvio;
use App\Enums\TipoServicio;
use App\Http\Requests\ActualizarEnvioRequest;
use App\Http\Resources\EnvioResource;
use App\Models\Envio;
use App\Models\OficinaPostal;
use App\Services\FormularioComparadorService;
use App\Services\NotificacionService;
use App\Services\VerificadorProductoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class PanelController extends Controller
{
    public function bandeja(Request $request): Response
    {
        $filtros = [
            'estado' => $request->string('estado')->toString(),
            'q' => $request->string('q')->toString(),
        ];

        $envios = Envio::query()
            ->with(['destinatario', 'user', 'oficinaPostal'])
            ->when($filtros['estado'], fn ($query, $estado) => $query->where('estado', $estado))
            ->when($filtros['q'], function ($query, $q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('codigo', 'like', "%{$q}%")
                        ->orWhere('pais_destino', 'like', "%{$q}%")
                        ->orWhereHas('destinatario', fn ($d) => $d->where('nombre_completo', 'like', "%{$q}%"))
                        ->orWhereHas('remitente', fn ($r) => $r->where('nombre_completo', 'like', "%{$q}%"));
                });
            })
            ->latest()
            ->limit(100)
            ->get();

        return Inertia::render('Panel/Bandeja', [
            'envios' => EnvioResource::collection($envios),
            'filtros' => $filtros,
            'estados' => $this->estadosDisponibles(),
            'metricas' => $this->metricas(),
        ]);
    }

    public function solicitud(Envio $envio, FormularioComparadorService $comparador): Response
    {
        $envio->load(['remitente', 'destinatario', 'articulos', 'user', 'atendidoPor', 'seguimientos', 'documentos']);

        return Inertia::render('Panel/Solicitud', [
            'envio' => (new EnvioResource($envio))->resolve(),
            'seguimientos' => $envio->seguimientos->map(fn ($s) => [
                'estado' => $s->estado->value,
                'label' => $s->estado->label(),
                'descripcion' => $s->descripcion,
                'fecha' => $s->fecha->format('d/m/Y H:i'),
            ]),
            'estados' => $this->estadosDisponibles(),
            'servicios' => $this->serviciosDisponibles(),
            'comparador' => $comparador->resumen(),
        ]);
    }

    public function editar(Envio $envio): Response
    {
        $envio->load(['remitente', 'destinatario', 'articulos']);

        return Inertia::render('Panel/Editar', [
            'envio' => [
                'codigo' => $envio->codigo,
                'tipo_servicio' => $envio->tipo_servicio->value,
                'pais_destino' => $envio->pais_destino,
                'oficina_deposito' => $envio->oficina_deposito,
                'tipo_envio' => $envio->tipo_envio,
                'naturaleza' => $envio->naturaleza->value,
                'divisa' => $envio->divisa,
                'peso_bruto' => $envio->peso_bruto ? (float) $envio->peso_bruto : null,
                'largo_cm' => $envio->largo_cm,
                'ancho_cm' => $envio->ancho_cm,
                'alto_cm' => $envio->alto_cm,
                'modalidad' => $envio->modalidad,
                'via' => $envio->via,
                'cantidad_bultos' => $envio->cantidad_bultos,
                'gastos_porte' => $envio->gastos_porte ? (float) $envio->gastos_porte : null,
                'valor_aduanas' => $envio->valor_aduanas ? (float) $envio->valor_aduanas : null,
                'valor_seguro' => $envio->valor_seguro ? (float) $envio->valor_seguro : null,
                'instruccion_no_entrega' => $envio->instruccion_no_entrega,
                'devolver_dias' => $envio->devolver_dias,
                'direccion_redireccion' => $envio->direccion_redireccion,
                'observaciones' => $envio->observaciones,
                'doc_comercial_tipo' => $envio->doc_comercial_tipo,
                'doc_comercial_serie' => $envio->doc_comercial_serie,
                'doc_comercial_numero' => $envio->doc_comercial_numero,
                'doc_comercial_razon' => $envio->doc_comercial_razon,
                'certificado_numero' => $envio->certificado_numero,
                'licencia_numero' => $envio->licencia_numero,
                'certificado_entidad' => $envio->certificado_entidad,
                'remitente' => $envio->remitente,
                'destinatario' => $envio->destinatario,
                'articulos' => $envio->articulos->map(fn ($a) => [
                    'descripcion' => $a->descripcion,
                    'cantidad' => $a->cantidad,
                    'peso_neto_gramos' => $a->peso_neto_gramos,
                    'valor' => (float) $a->valor,
                    'pais_origen' => $a->pais_origen,
                    'codigo_hs' => $a->codigo_hs,
                ]),
            ],
            'oficinas' => OficinaPostal::orderBy('nombre')->get(['codigo', 'nombre', 'ciudad', 'latitud', 'longitud']),
        ]);
    }

    public function actualizar(ActualizarEnvioRequest $request, Envio $envio, VerificadorProductoService $verificador): RedirectResponse
    {
        $datos = $request->validated();
        $oficina = OficinaPostal::where('nombre', $datos['oficina_deposito'])->first();

        DB::transaction(function () use ($datos, $envio, $verificador, $oficina) {
            $camposEnvio = collect($datos)->except(['remitente', 'destinatario', 'articulos'])->all();
            $envio->update([...$camposEnvio, 'oficina_postal_id' => $oficina?->id]);

            $envio->remitente()->update($datos['remitente']);
            $envio->destinatario()->update($datos['destinatario']);

            $envio->articulos()->delete();
            foreach ($datos['articulos'] as $articulo) {
                $envio->articulos()->create([
                    ...$articulo,
                    'categoria' => $verificador->verificar($articulo['descripcion'])->value,
                ]);
            }
        });

        return redirect()
            ->route('panel.solicitud', $envio->codigo)
            ->with('success', "Datos del envío {$envio->codigo} actualizados.");
    }

    public function actualizarEstado(Request $request, Envio $envio, NotificacionService $notificaciones): RedirectResponse
    {
        $datos = $request->validate([
            'estado' => ['required', Rule::enum(EstadoEnvio::class)],
        ]);

        $estado = EstadoEnvio::from($datos['estado']);

        $envio->update([
            'estado' => $estado->value,
            'atendido_por' => $request->user()->id,
        ]);

        $envio->seguimientos()->create([
            'estado' => $estado->value,
            'descripcion' => $estado->descripcion(),
            'fecha' => now(),
        ]);

        $notificaciones->notificarEstado($envio);

        return back()->with('success', "Estado actualizado a: {$estado->label()}");
    }

    public function cambiarServicio(Request $request, Envio $envio): RedirectResponse
    {
        $datos = $request->validate([
            'tipo_servicio' => ['required', Rule::enum(TipoServicio::class)],
        ]);

        $nuevo = TipoServicio::from($datos['tipo_servicio']);
        $anterior = $envio->tipo_servicio;

        $envio->update(['tipo_servicio' => $nuevo->value]);

        return back()->with(
            'success',
            "Reclasificado de {$anterior->formulario()} a {$nuevo->formulario()}. Los datos se conservaron."
        );
    }

    public function procesar(Request $request, Envio $envio, NotificacionService $notificaciones): RedirectResponse
    {
        $envio->update([
            'estado' => EstadoEnvio::ADMITIDO->value,
            'atendido_por' => $request->user()->id,
            'procesado_at' => now(),
        ]);

        $envio->seguimientos()->create([
            'estado' => EstadoEnvio::ADMITIDO->value,
            'descripcion' => EstadoEnvio::ADMITIDO->descripcion(),
            'fecha' => now(),
        ]);

        $notificaciones->notificarEstado($envio);

        return back()->with('success', "Envío {$envio->codigo} admitido y procesado.");
    }

    private function estadosDisponibles(): array
    {
        return array_map(fn (EstadoEnvio $e) => [
            'valor' => $e->value,
            'label' => $e->label(),
        ], EstadoEnvio::cases());
    }

    private function serviciosDisponibles(): array
    {
        return array_map(fn (TipoServicio $s) => [
            'valor' => $s->value,
            'label' => $s->label(),
            'formulario' => $s->formulario(),
        ], TipoServicio::cases());
    }

    private function metricas(): array
    {
        return [
            'total' => Envio::count(),
            'pendientes' => Envio::where('estado', EstadoEnvio::PENDIENTE_DEPOSITO->value)->count(),
            'admitidos' => Envio::where('estado', EstadoEnvio::ADMITIDO->value)->count(),
            'hoy' => Envio::whereDate('created_at', today())->count(),
        ];
    }
}
