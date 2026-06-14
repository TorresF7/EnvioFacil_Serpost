<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnvioResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'codigo' => $this->codigo,
            'tipo_servicio' => $this->tipo_servicio->value,
            'servicio_label' => $this->tipo_servicio->label(),
            'formulario' => $this->tipo_servicio->formulario(),
            'pais_destino' => $this->pais_destino,
            'oficina_deposito' => $this->oficina_deposito,
            'tipo_envio' => $this->tipo_envio,
            'naturaleza' => $this->naturaleza->value,
            'naturaleza_label' => $this->naturaleza->label(),
            'divisa' => $this->divisa,
            'peso_bruto' => $this->peso_bruto ? (float) $this->peso_bruto : null,
            'largo_cm' => $this->largo_cm,
            'ancho_cm' => $this->ancho_cm,
            'alto_cm' => $this->alto_cm,
            'modalidad' => $this->modalidad,
            'via' => $this->via,
            'cantidad_bultos' => $this->cantidad_bultos,
            'gastos_porte' => $this->gastos_porte ? (float) $this->gastos_porte : null,
            'valor_aduanas' => $this->valor_aduanas ? (float) $this->valor_aduanas : null,
            'valor_seguro' => $this->valor_seguro ? (float) $this->valor_seguro : null,
            'instruccion_no_entrega' => $this->instruccion_no_entrega,
            'devolver_dias' => $this->devolver_dias,
            'direccion_redireccion' => $this->direccion_redireccion,
            'observaciones' => $this->observaciones,
            'doc_comercial' => [
                'tipo' => $this->doc_comercial_tipo,
                'serie' => $this->doc_comercial_serie,
                'numero' => $this->doc_comercial_numero,
                'razon' => $this->doc_comercial_razon,
            ],
            'certificado' => [
                'numero' => $this->certificado_numero,
                'licencia' => $this->licencia_numero,
                'entidad' => $this->certificado_entidad,
            ],
            'estado' => $this->estado->value,
            'estado_label' => $this->estado->label(),
            'creado' => $this->created_at?->format('d/m/Y H:i'),
            'procesado' => $this->procesado_at?->format('d/m/Y H:i'),
            'descargar_url' => route('envio.descargar', $this->codigo),
            'rotulado_url' => route('envio.rotulado', $this->codigo),
            'documentos' => $this->whenLoaded('documentos', fn () => $this->documentos->map(fn ($doc) => [
                'id' => $doc->id,
                'tipo' => $doc->tipo,
                'entidad' => $doc->entidad,
                'nombre' => $doc->nombre_original,
                'tamano' => $doc->tamano_bytes,
                'descargar_url' => route('envio.documentos.descargar', [$this->codigo, $doc->id]),
            ])),
            'remitente' => $this->whenLoaded('remitente'),
            'destinatario' => $this->whenLoaded('destinatario'),
            'articulos' => ArticuloResource::collection($this->whenLoaded('articulos')),
            'cliente' => $this->whenLoaded('user', fn () => [
                'name' => $this->user?->name,
                'email' => $this->user?->email,
            ]),
            'atendido_por' => $this->whenLoaded('atendidoPor', fn () => $this->atendidoPor?->name),
        ];
    }
}
