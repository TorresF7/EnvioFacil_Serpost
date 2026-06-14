<?php

namespace App\Services;

use App\Models\Envio;

class FormularioGeneradorService
{
    public function construir(Envio $envio): array
    {
        $envio->loadMissing(['remitente', 'destinatario', 'articulos']);

        $articulos = $envio->articulos;

        return [
            'formulario' => $envio->tipo_servicio->formulario(),
            'servicio' => $envio->tipo_servicio->label(),
            'codigo' => $envio->codigo,
            'pais_destino' => $envio->pais_destino,
            'oficina' => $envio->oficina_deposito,
            'tipo_envio' => $envio->tipo_envio,
            'naturaleza' => $envio->naturaleza->label(),
            'naturaleza_valor' => $envio->naturaleza->value,
            'divisa' => $envio->divisa,
            'peso_bruto' => $envio->peso_bruto ? (float) $envio->peso_bruto : null,
            'pais_origen' => 'Perú',
            'observaciones' => $envio->observaciones,
            'remitente' => $envio->remitente,
            'destinatario' => $envio->destinatario,
            'documento_comercial' => $this->documentoComercial($envio),
            'certificado' => $this->certificado($envio),
            'articulos' => $articulos->map(fn ($a) => [
                'descripcion' => $a->descripcion,
                'cantidad' => $a->cantidad,
                'codigo_hs' => $a->codigo_hs,
                'peso_neto_gramos' => $a->peso_neto_gramos,
                'valor' => (float) $a->valor,
                'pais_origen' => $a->pais_origen,
            ])->all(),
            'totales' => [
                'cantidad' => (int) $articulos->sum('cantidad'),
                'peso_gramos' => (int) $articulos->sum('peso_neto_gramos'),
                'valor' => (float) $articulos->sum('valor'),
            ],
            'valor_aduanas' => $envio->valor_aduanas,
            'valor_seguro' => $envio->valor_seguro,
            'gastos_porte' => $envio->gastos_porte,
            'modalidad' => $envio->modalidad,
            'via' => $envio->via,
            'cantidad_bultos' => $envio->cantidad_bultos,
            'instruccion_no_entrega' => $envio->instruccion_no_entrega,
            'devolver_dias' => $envio->devolver_dias,
        ];
    }

    private function documentoComercial(Envio $envio): ?array
    {
        if (! $envio->doc_comercial_tipo) {
            return null;
        }

        return [
            'tipo' => $envio->doc_comercial_tipo,
            'serie' => $envio->doc_comercial_serie,
            'numero' => $envio->doc_comercial_numero,
            'razon' => $envio->doc_comercial_razon,
        ];
    }

    private function certificado(Envio $envio): ?array
    {
        if (! $envio->certificado_numero && ! $envio->licencia_numero) {
            return null;
        }

        return [
            'certificado' => $envio->certificado_numero,
            'licencia' => $envio->licencia_numero,
            'entidad' => $envio->certificado_entidad,
        ];
    }
}
