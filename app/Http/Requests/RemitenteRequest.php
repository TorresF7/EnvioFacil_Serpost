<?php

namespace App\Http\Requests;

use App\Enums\TipoDocumento;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RemitenteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return self::reglas();
    }

    public static function reglas(string $prefijo = ''): array
    {
        $campo = fn (string $nombre) => $prefijo ? "{$prefijo}.{$nombre}" : $nombre;

        return [
            $campo('tipo_documento') => ['required', Rule::enum(TipoDocumento::class)],
            $campo('numero_documento') => ['required', 'string', 'max:20'],
            $campo('nombre_completo') => ['required', 'string', 'max:255'],
            $campo('direccion') => ['required', 'string', 'max:255'],
            $campo('ciudad') => ['required', 'string', 'max:120'],
            $campo('departamento') => ['nullable', 'string', 'max:120'],
            $campo('codigo_postal') => ['nullable', 'string', 'max:20'],
            $campo('telefono') => ['required', 'string', 'max:30'],
            $campo('email') => ['nullable', 'email', 'max:255'],
            $campo('depositante_es_remitente') => ['boolean'],
            $campo('depositante_nombre') => ['nullable', 'string', 'max:255'],
            $campo('depositante_tipo_doc') => ['nullable', 'string', 'max:20'],
            $campo('depositante_numero_doc') => ['nullable', 'string', 'max:30'],
            $campo('depositante_direccion') => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre_completo.required' => 'El nombre del remitente es obligatorio',
            'numero_documento.required' => 'El número de documento es obligatorio',
            'direccion.required' => 'La dirección es obligatoria',
            'telefono.required' => 'El teléfono es obligatorio',
        ];
    }
}
