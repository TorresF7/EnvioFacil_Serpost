<?php

namespace App\Http\Controllers;

use App\Services\VerificadorProductoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class VerificadorController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Verificador');
    }

    public function verificar(Request $request, VerificadorProductoService $verificador): JsonResponse
    {
        $request->validate([
            'descripcion' => ['required', 'string', 'max:255'],
        ]);

        $resultado = $verificador->verificarDetallado($request->string('descripcion'));

        return response()->json([
            'categoria' => $resultado['categoria']->value,
            'label' => $resultado['categoria']->label(),
            'icono' => $resultado['categoria']->icono(),
            'mensaje' => $resultado['categoria']->mensaje(),
            'bloquea' => $resultado['categoria']->bloquea(),
            'palabra' => $resultado['palabra'],
            'entidad' => $resultado['entidad'],
            'documento' => $resultado['documento'],
        ]);
    }
}
