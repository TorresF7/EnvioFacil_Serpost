<?php

namespace App\Http\Requests;

use App\Enums\InstruccionNoEntrega;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaqueteRequest extends FormRequest
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
            'peso_bruto' => ['required', 'numeric', 'min:0.001', 'max:31.5'],
            'largo_cm' => ['nullable', 'integer', 'min:1', 'max:300'],
            'ancho_cm' => ['nullable', 'integer', 'min:1', 'max:300'],
            'alto_cm' => ['nullable', 'integer', 'min:1', 'max:300'],
            'observaciones' => ['nullable', 'string', 'max:500'],
            'valor_aduanas' => ['nullable', 'numeric', 'min:0'],
            'valor_seguro' => ['nullable', 'numeric', 'min:0'],
            'instruccion_no_entrega' => ['nullable', Rule::enum(InstruccionNoEntrega::class)],
            'devolver_dias' => ['nullable', 'integer', 'min:0', 'max:365'],
            'direccion_redireccion' => ['nullable', 'string', 'max:255'],
            'modalidad' => ['nullable', 'in:prioritario,economico'],
            'via' => ['nullable', 'in:aereo,superficie'],
            'gastos_porte' => ['nullable', 'numeric', 'min:0'],
            'cantidad_bultos' => ['nullable', 'integer', 'min:1', 'max:99'],
        ];
    }

    public function messages(): array
    {
        return [
            'peso_bruto.required' => 'Indica el peso total del paquete',
            'peso_bruto.max' => 'El peso máximo permitido es 31.5 kg',
        ];
    }
}
