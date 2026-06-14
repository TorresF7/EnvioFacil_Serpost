<?php

namespace App\Http\Requests;

use App\Enums\NaturalezaEnvio;
use App\Enums\TipoServicio;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContenidoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return self::reglas();
    }

    public static function reglas(): array
    {
        return [
            'tipo_servicio' => ['required', Rule::enum(TipoServicio::class)],
            'pais_destino' => ['required', 'string', 'max:120'],
            'oficina_deposito' => ['required', 'string', 'max:160'],
            'tipo_envio' => ['required', 'string', 'in:mercaderia,documento'],
            'naturaleza' => ['required', Rule::enum(NaturalezaEnvio::class)],
            'divisa' => ['required', 'string', 'max:3'],

            'articulos' => ['required', 'array', 'min:1'],
            'articulos.*.descripcion' => ['required', 'string', 'max:255'],
            'articulos.*.cantidad' => ['required', 'integer', 'min:1'],
            'articulos.*.peso_neto_gramos' => ['required', 'integer', 'min:1'],
            'articulos.*.valor' => ['required', 'numeric', 'min:0'],
            'articulos.*.pais_origen' => ['required', 'string', 'max:120'],
            'articulos.*.codigo_hs' => ['nullable', 'string', 'max:20'],

            'doc_comercial_tipo' => ['nullable', 'string', 'max:40'],
            'doc_comercial_serie' => ['nullable', 'string', 'max:40'],
            'doc_comercial_numero' => ['nullable', 'string', 'max:40'],
            'doc_comercial_razon' => ['nullable', 'string', 'max:255'],

            'certificado_numero' => ['nullable', 'string', 'max:60'],
            'licencia_numero' => ['nullable', 'string', 'max:60'],
            'certificado_entidad' => ['nullable', 'string', 'max:120'],
        ];
    }

    public function messages(): array
    {
        return [
            'pais_destino.required' => 'Selecciona el país de destino',
            'oficina_deposito.required' => 'Selecciona la oficina donde depositarás',
            'articulos.required' => 'Agrega al menos un artículo al envío',
            'articulos.min' => 'Agrega al menos un artículo al envío',
            'articulos.*.descripcion.required' => 'Describe el producto',
            'articulos.*.peso_neto_gramos.required' => 'Indica el peso del producto',
        ];
    }
}
