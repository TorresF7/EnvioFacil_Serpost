<?php

namespace App\Http\Controllers;

use App\Models\Envio;
use App\Services\FormularioGeneradorService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FormularioController extends Controller
{
    public function descargar(Request $request, Envio $envio, FormularioGeneradorService $generador): Response
    {
        $usuario = $request->user();
        $puede = $usuario->esStaff() || $envio->user_id === null || $envio->user_id === $usuario->id;
        abort_unless($puede, 403);

        $datos = $generador->construir($envio);

        $orientacion = $datos['formulario'] === 'CN22' ? 'portrait' : 'landscape';

        $pdf = Pdf::loadView('formulario.imprimible', ['form' => $datos])
            ->setPaper('a4', $orientacion);

        return $pdf->download("{$datos['formulario']}-{$envio->codigo}.pdf");
    }
}
