<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActualizarEnvioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return array_merge(
            ContenidoRequest::reglas(),
            PaqueteRequest::reglas(),
            RemitenteRequest::reglas('remitente'),
            DestinatarioRequest::reglas('destinatario'),
            [
                'remitente.empresa' => ['nullable', 'string', 'max:255'],
                'remitente.ruc' => ['nullable', 'string', 'max:20'],
                'destinatario.empresa' => ['nullable', 'string', 'max:255'],
                'destinatario.referencia_importador' => ['nullable', 'string', 'max:60'],
            ],
        );
    }

    public function messages(): array
    {
        return array_merge(
            (new ContenidoRequest)->messages(),
            (new PaqueteRequest)->messages(),
        );
    }
}
