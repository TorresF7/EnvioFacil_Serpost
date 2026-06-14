<?php

namespace App\Models;

use App\Enums\CategoriaArticulo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Articulo extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'cantidad' => 'integer',
            'peso_neto_gramos' => 'integer',
            'valor' => 'decimal:2',
            'categoria' => CategoriaArticulo::class,
        ];
    }

    public function envio(): BelongsTo
    {
        return $this->belongsTo(Envio::class);
    }
}
