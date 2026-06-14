<?php

namespace App\Http\Controllers;

use App\Models\DocumentoAdjunto;
use App\Models\Envio;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentoController extends Controller
{
    public function store(Request $request, Envio $envio): JsonResponse
    {
        $this->autorizar($request, $envio);

        $datos = $request->validate([
            'archivo' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'tipo' => ['nullable', 'in:certificado,licencia,factura,otro'],
            'entidad' => ['nullable', 'string', 'max:120'],
        ]);

        $archivo = $request->file('archivo');
        $nombre = Str::random(8) . '_' . $archivo->getClientOriginalName();
        $ruta = $archivo->storeAs("certificados/{$envio->codigo}", $nombre, 'local');

        $documento = $envio->documentos()->create([
            'tipo' => $datos['tipo'] ?? 'certificado',
            'entidad' => $datos['entidad'] ?? null,
            'nombre_original' => $archivo->getClientOriginalName(),
            'ruta' => $ruta,
            'mime' => $archivo->getClientMimeType(),
            'tamano_bytes' => $archivo->getSize(),
            'subido_por' => $request->user()->id,
        ]);

        return response()->json([
            'id' => $documento->id,
            'tipo' => $documento->tipo,
            'entidad' => $documento->entidad,
            'nombre' => $documento->nombre_original,
            'tamano' => $documento->tamano_bytes,
            'descargar_url' => route('envio.documentos.descargar', [$envio->codigo, $documento->id]),
        ], 201);
    }

    public function descargar(Request $request, Envio $envio, DocumentoAdjunto $documento): StreamedResponse
    {
        $this->autorizar($request, $envio);
        abort_unless($documento->envio_id === $envio->id, 404);

        return Storage::disk('local')->download($documento->ruta, $documento->nombre_original);
    }

    public function destroy(Request $request, Envio $envio, DocumentoAdjunto $documento): JsonResponse
    {
        $this->autorizar($request, $envio);
        abort_unless($documento->envio_id === $envio->id, 404);

        Storage::disk('local')->delete($documento->ruta);
        $documento->delete();

        return response()->json(['ok' => true]);
    }

    private function autorizar(Request $request, Envio $envio): void
    {
        $usuario = $request->user();
        $puede = $usuario->esStaff() || $envio->user_id === null || $envio->user_id === $usuario->id;
        abort_unless($puede, 403);
    }
}
