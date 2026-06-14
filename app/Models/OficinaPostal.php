<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OficinaPostal extends Model
{
    protected $guarded = [];

    protected $table = 'oficinas_postales';

    protected function casts(): array
    {
        return [
            'latitud' => 'float',
            'longitud' => 'float',
            'es_principal' => 'boolean',
        ];
    }
}
