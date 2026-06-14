<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DestinatarioRequest extends FormRequest
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
            $campo('nombre_completo') => ['required', 'string', 'max:255'],
            $campo('direccion') => ['required', 'string', 'max:255'],
            $campo('ciudad') => ['required', 'string', 'max:120'],
            $campo('estado_region') => ['nullable', 'string', 'max:120'],
            $campo('codigo_postal') => ['required', 'string', 'max:20'],
            $campo('pais') => ['required', 'string', 'max:120'],
            $campo('telefono') => ['nullable', 'string', 'max:30'],
            $campo('email') => ['nullable', 'email', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre_completo.required' => 'El nombre del destinatario es obligatorio',
            'direccion.required' => 'La dirección de destino es obligatoria',
            'codigo_postal.required' => 'El código postal es obligatorio',
            'pais.required' => 'El país de destino es obligatorio',
        ];
    }
}
