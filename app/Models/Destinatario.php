<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Destinatario extends Model
{
    protected $guarded = [];

    public function envio(): BelongsTo
    {
        return $this->belongsTo(Envio::class);
    }
}
