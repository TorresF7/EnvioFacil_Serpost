<?php

namespace App\Http\Controllers;

use App\Data\ReniecSimulado;
use Illuminate\Http\JsonResponse;

class ReniecController extends Controller
{
    public function consultar(string $dni): JsonResponse
    {
        $nombres = ReniecSimulado::consultar($dni);

        if ($nombres === null) {
            return response()->json(['message' => 'El DNI debe tener 8 dígitos.'], 422);
        }

        return response()->json([
            'dni' => $dni,
            'nombres' => $nombres,
            'simulado' => true,
        ]);
    }
}
