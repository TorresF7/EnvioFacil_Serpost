<?php

namespace App\Services;

use App\Models\Envio;
use Illuminate\Support\Str;

class QrGeneradorService
{

    public function generarCodigo(): string
    {
        $aleatorio = Str::upper(Str::random(6));

        return 'SRP-'.$aleatorio;
    }

    public function payload(Envio $envio): array
    {
        $envio->loadMissing(['remitente', 'destinatario', 'articulos']);

        return [
            'codigo' => $envio->codigo,
            'servicio' => $envio->tipo_servicio->value,
            'formulario' => $envio->tipo_servicio->formulario(),
            'pais_destino' => $envio->pais_destino,
            'oficina' => $envio->oficina_deposito,
            'tipo_envio' => $envio->tipo_envio,
            'naturaleza' => $envio->naturaleza->value,
            'divisa' => $envio->divisa,
            'peso_bruto' => $envio->peso_bruto,
            'remitente' => $envio->remitente?->only([
                'tipo_documento', 'numero_documento', 'nombre_completo',
                'direccion', 'ciudad', 'departamento', 'codigo_postal', 'telefono', 'email',
            ]),
            'destinatario' => $envio->destinatario?->only([
                'nombre_completo', 'direccion', 'ciudad', 'estado_region',
                'codigo_postal', 'pais', 'telefono', 'email',
            ]),
            'articulos' => $envio->articulos->map(fn ($a) => [
                'descripcion' => $a->descripcion,
                'cantidad' => $a->cantidad,
                'peso_neto_gramos' => $a->peso_neto_gramos,
                'valor' => $a->valor,
                'pais_origen' => $a->pais_origen,
                'codigo_hs' => $a->codigo_hs,
            ])->all(),
        ];
    }

    public function payloadJson(Envio $envio): string
    {
        return json_encode($this->payload($envio), JSON_UNESCAPED_UNICODE);
    }
}
