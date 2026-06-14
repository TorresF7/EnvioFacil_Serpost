<?php

namespace App\Http\Controllers;

use App\Enums\EstadoEnvio;
use App\Http\Requests\GuardarEnvioRequest;
use App\Models\Envio;
use App\Models\OficinaPostal;
use App\Services\QrGeneradorService;
use App\Services\VerificadorProductoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class PreAdmisionController extends Controller
{
    public function index(Request $request): Response
    {
        $precarga = null;
        if ($id = $request->query('repetir')) {
            $envio = $request->user()?->envios()
                ->with(['remitente', 'destinatario'])
                ->whereKey($id)
                ->first();

            if ($envio) {
                $precarga = $this->precargaDesde($envio);
            }
        }

        return Inertia::render('PreAdmision/Index', [
            'oficinas' => OficinaPostal::orderByDesc('es_principal')->orderBy('nombre')->get(),
            'precarga' => $precarga,
        ]);
    }

    private function precargaDesde(Envio $envio): array
    {
        $r = $envio->remitente;
        $d = $envio->destinatario;

        return [
            'remitente' => $r ? [
                'tipo_documento' => $r->tipo_documento?->value ?? 'dni',
                'numero_documento' => $r->numero_documento ?? '',
                'nombre_completo' => $r->nombre_completo ?? '',
                'empresa' => $r->empresa ?? '',
                'ruc' => $r->ruc ?? '',
                'direccion' => $r->direccion ?? '',
                'ciudad' => $r->ciudad ?? '',
                'departamento' => $r->departamento ?? '',
                'codigo_postal' => $r->codigo_postal ?? '',
                'telefono' => $r->telefono ?? '',
                'email' => $r->email ?? '',
                'depositante_es_remitente' => (bool) $r->depositante_es_remitente,
                'depositante_nombre' => $r->depositante_nombre ?? '',
                'depositante_tipo_doc' => $r->depositante_tipo_doc ?? 'dni',
                'depositante_numero_doc' => $r->depositante_numero_doc ?? '',
                'depositante_direccion' => $r->depositante_direccion ?? '',
            ] : null,
            'destinatario' => $d ? [
                'nombre_completo' => $d->nombre_completo ?? '',
                'empresa' => $d->empresa ?? '',
                'referencia_importador' => $d->referencia_importador ?? '',
                'direccion' => $d->direccion ?? '',
                'ciudad' => $d->ciudad ?? '',
                'estado_region' => $d->estado_region ?? '',
                'codigo_postal' => $d->codigo_postal ?? '',
                'pais' => $d->pais ?? '',
                'telefono' => $d->telefono ?? '',
                'email' => $d->email ?? '',
            ] : null,
        ];
    }

    public function guardar(
        GuardarEnvioRequest $request,
        QrGeneradorService $qr,
        VerificadorProductoService $verificador,
    ): JsonResponse {
        $datos = $request->validated();
        $oficina = OficinaPostal::where('nombre', $datos['oficina_deposito'])->first();
        $userId = $request->user()?->id;

        $envio = DB::transaction(function () use ($datos, $qr, $verificador, $oficina, $userId) {
            $envio = Envio::create([
                ...$this->soloEnvio($datos),
                'user_id' => $userId,
                'oficina_postal_id' => $oficina?->id,
                'codigo' => $qr->generarCodigo(),
                'estado' => EstadoEnvio::PENDIENTE_DEPOSITO->value,
            ]);

            $envio->remitente()->create($datos['remitente']);
            $envio->destinatario()->create($datos['destinatario']);

            foreach ($datos['articulos'] as $articulo) {
                $envio->articulos()->create([
                    ...$articulo,
                    'categoria' => $verificador->verificar($articulo['descripcion'])->value,
                ]);
            }

            $this->crearSeguimientoInicial($envio);

            return $envio;
        });

        return response()->json([
            'codigo' => $envio->codigo,
            'descargar_url' => route('envio.descargar', $envio),
            'rotulado_url' => route('envio.rotulado', $envio),
            'qr_payload' => $qr->payloadJson($envio),
        ]);
    }

    private function soloEnvio(array $datos): array
    {
        return collect($datos)->except(['remitente', 'destinatario', 'articulos'])->all();
    }

    private function crearSeguimientoInicial(Envio $envio): void
    {
        $envio->seguimientos()->create([
            'estado' => EstadoEnvio::PREADMISION->value,
            'descripcion' => EstadoEnvio::PREADMISION->descripcion(),
            'fecha' => now(),
        ]);

        $envio->seguimientos()->create([
            'estado' => EstadoEnvio::PENDIENTE_DEPOSITO->value,
            'descripcion' => EstadoEnvio::PENDIENTE_DEPOSITO->descripcion(),
            'fecha' => now(),
        ]);
    }
}
