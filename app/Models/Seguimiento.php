<?php

namespace App\Models;

use App\Enums\EstadoEnvio;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Seguimiento extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'estado' => EstadoEnvio::class,
            'fecha' => 'datetime',
        ];
    }

    public function envio(): BelongsTo
    {
        return $this->belongsTo(Envio::class);
    }
}
