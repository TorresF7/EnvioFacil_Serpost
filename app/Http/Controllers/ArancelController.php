<?php

namespace App\Http\Controllers;

use App\Services\ArancelService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArancelController extends Controller
{
    public function sugerir(Request $request, ArancelService $arancel): JsonResponse
    {
        $request->validate([
            'descripcion' => ['required', 'string', 'max:255'],
        ]);

        return response()->json([
            'sugerencias' => $arancel->sugerir($request->string('descripcion')),
        ]);
    }
}
