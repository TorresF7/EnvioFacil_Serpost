<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentoAdjunto extends Model
{
    protected $table = 'documentos';

    protected $guarded = [];

    public function envio(): BelongsTo
    {
        return $this->belongsTo(Envio::class);
    }

    public function subidoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'subido_por');
    }
}
