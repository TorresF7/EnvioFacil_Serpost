<?php

namespace App\Http\Controllers;

use App\Models\Envio;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RotuladoController extends Controller
{
    public function descargar(Request $request, Envio $envio): Response
    {
        $usuario = $request->user();
        $puede = $usuario->esStaff() || $envio->user_id === null || $envio->user_id === $usuario->id;
        abort_unless($puede, 403);

        $envio->loadMissing(['remitente', 'destinatario']);

        $pdf = Pdf::loadView('rotulado.etiqueta', [
            'envio' => $envio,
            'remitente' => $envio->remitente,
            'destinatario' => $envio->destinatario,
            'servicio' => $envio->tipo_servicio->label(),
            'formulario' => $envio->tipo_servicio->formulario(),
        ])->setPaper('a4');

        return $pdf->download("rotulo-{$envio->codigo}.pdf");
    }
}
