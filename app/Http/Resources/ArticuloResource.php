<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticuloResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'descripcion' => $this->descripcion,
            'cantidad' => $this->cantidad,
            'peso_neto_gramos' => $this->peso_neto_gramos,
            'valor' => (float) $this->valor,
            'pais_origen' => $this->pais_origen,
            'codigo_hs' => $this->codigo_hs,
            'categoria' => $this->categoria?->value,
            'categoria_label' => $this->categoria?->label(),
            'categoria_icono' => $this->categoria?->icono(),
        ];
    }
}
