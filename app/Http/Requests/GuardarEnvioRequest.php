<?php

namespace App\Http\Requests;

use App\Enums\InstruccionNoEntrega;
use App\Enums\TipoDocumento;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class GuardarEnvioRequest extends FormRequest
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
        );
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $this->validarDniRemitente($validator);
            $this->validarRedireccion($validator);
        });
    }

    private function validarDniRemitente(Validator $validator): void
    {
        $tipo = $this->input('remitente.tipo_documento');
        $numero = (string) $this->input('remitente.numero_documento');

        if ($tipo === TipoDocumento::DNI->value && ! preg_match('/^\d{8}$/', $numero)) {
            $validator->errors()->add('remitente.numero_documento', 'El DNI debe tener exactamente 8 dígitos');
        }
    }

    private function validarRedireccion(Validator $validator): void
    {
        $instruccion = $this->input('instruccion_no_entrega');
        $direccion = $this->input('direccion_redireccion');

        if ($instruccion === InstruccionNoEntrega::REDIRIGIR->value && blank($direccion)) {
            $validator->errors()->add('direccion_redireccion', 'Indica la dirección de redirección');
        }
    }

    public function messages(): array
    {
        return array_merge(
            (new ContenidoRequest)->messages(),
            (new PaqueteRequest)->messages(),
        );
    }
}
