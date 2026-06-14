<?php

namespace App\Models;

use App\Enums\TipoDocumento;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Remitente extends Model
{
    protected $guarded = [];

    protected $table = 'remitentes';

    protected function casts(): array
    {
        return [
            'tipo_documento' => TipoDocumento::class,
            'depositante_es_remitente' => 'boolean',
        ];
    }

    public function envio(): BelongsTo
    {
        return $this->belongsTo(Envio::class);
    }
}
