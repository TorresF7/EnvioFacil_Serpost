<?php

namespace App\Services;

use App\Enums\TipoServicio;

class FormularioComparadorService
{

    public function camposCompartidos(): array
    {
        return [
            'Datos del remitente',
            'Datos del destinatario',
            'Artículos (descripción, cantidad, peso, valor, origen, HS)',
            'País de destino y oficina',
            'Naturaleza del envío',
            'Peso del paquete',
        ];
    }

    public function camposEspecificos(TipoServicio $servicio): array
    {
        $especificos = match ($servicio) {
            TipoServicio::PEQUENO_PAQUETE => [
                'Hasta 2 kg',
                'Declaración simple (sin seguro ni flete detallado)',
            ],
            TipoServicio::EMS => [
                'Hasta 30 kg',
                'Gastos de porte / flete',
                'Seguro',
                'Certificados y licencias',
                'Referencia del importador (RUC/DNI)',
            ],
            TipoServicio::ENCOMIENDA => [
                'Hasta 31.5 kg',
                'Valor declarado (aduanas y seguro)',
                'Instrucciones de no entrega',
                'Porte / cobro',
            ],
        };

        return [
            'nombre' => $servicio->label(),
            'formulario' => $servicio->formulario(),
            'especificos' => $especificos,
        ];
    }

    public function resumen(): array
    {
        $formularios = [];
        foreach (TipoServicio::cases() as $servicio) {
            $formularios[$servicio->value] = $this->camposEspecificos($servicio);
        }

        return [
            'compartidos' => $this->camposCompartidos(),
            'formularios' => $formularios,
        ];
    }
}
