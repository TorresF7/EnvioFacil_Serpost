<?php

namespace App\Models;

use App\Enums\EstadoEnvio;
use App\Enums\NaturalezaEnvio;
use App\Enums\TipoServicio;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Envio extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'tipo_servicio' => TipoServicio::class,
            'naturaleza' => NaturalezaEnvio::class,
            'estado' => EstadoEnvio::class,
            'peso_bruto' => 'decimal:3',
            'valor_aduanas' => 'decimal:2',
            'valor_seguro' => 'decimal:2',
            'gastos_porte' => 'decimal:2',
            'procesado_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function oficinaPostal(): BelongsTo
    {
        return $this->belongsTo(OficinaPostal::class);
    }

    public function atendidoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'atendido_por');
    }

    public function remitente(): HasOne
    {
        return $this->hasOne(Remitente::class);
    }

    public function destinatario(): HasOne
    {
        return $this->hasOne(Destinatario::class);
    }

    public function articulos(): HasMany
    {
        return $this->hasMany(Articulo::class);
    }

    public function seguimientos(): HasMany
    {
        return $this->hasMany(Seguimiento::class)->orderBy('fecha');
    }

    public function documentos(): HasMany
    {
        return $this->hasMany(DocumentoAdjunto::class)->latest();
    }

    public function getRouteKeyName(): string
    {
        return 'codigo';
    }
}
