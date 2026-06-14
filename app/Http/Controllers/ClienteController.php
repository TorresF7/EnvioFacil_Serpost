<?php

namespace App\Http\Controllers;

use App\Http\Resources\EnvioResource;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ClienteController extends Controller
{
    public function index(Request $request): Response
    {
        $envios = $request->user()->envios()
            ->latest()
            ->take(5)
            ->get();

        return Inertia::render('Cliente/Home', [
            'usuario' => $request->user()->only(['name', 'email']),
            'ultimosEnvios' => EnvioResource::collection($envios),
            'destinosRecientes' => $this->destinosRecientes($request),
        ]);
    }

    private function destinosRecientes(Request $request): \Illuminate\Support\Collection
    {
        return $request->user()->envios()
            ->with('destinatario')
            ->whereHas('destinatario')
            ->latest()
            ->take(20)
            ->get()
            ->unique(fn ($e) => mb_strtolower(trim(
                ($e->destinatario->nombre_completo ?? '').'|'
                .($e->destinatario->pais ?? '').'|'
                .($e->destinatario->ciudad ?? '')
            )))
            ->take(5)
            ->values()
            ->map(fn ($e) => [
                'id' => $e->id,
                'nombre' => $e->destinatario->nombre_completo,
                'pais' => $e->destinatario->pais,
                'ciudad' => $e->destinatario->ciudad,
                'creado' => $e->created_at?->format('d/m/Y'),
            ]);
    }

    public function misEnvios(Request $request): Response
    {
        $envios = $request->user()->envios()
            ->with(['destinatario', 'documentos'])
            ->latest()
            ->get();

        return Inertia::render('Cliente/MisEnvios', [
            'envios' => EnvioResource::collection($envios),
        ]);
    }
}
