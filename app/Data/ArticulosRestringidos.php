<?php

namespace App\Data;

final class ArticulosRestringidos
{

    public const CONTROLADOS = [
        'tramadol', 'tramal', 'diazepam', 'valium', 'clonazepam', 'rivotril', 'alprazolam',
        'lorazepam', 'zolpidem', 'morfina', 'fentanilo', 'fentanil', 'codeina', 'oxicodona',
        'metadona', 'fenobarbital', 'midazolam', 'petidina', 'ketamina',
    ];

    public const PROHIBIDOS = [

        'dinero en efectivo', 'efectivo', 'dinero', 'billete', 'billetes', 'papel moneda',
        'cheque', 'cheques', 'tarjeta de credito', 'valores al portador',

        'droga', 'drogas', 'estupefaciente', 'sicotropico', 'psicotropico', 'cocaina', 'marihuana', 'cannabis',

        'fosforo', 'fosforos', 'cerillo', 'cerillos', 'encendedor', 'encendedores',
        'gasolina', 'liquido inflamable', 'solido inflamable', 'pintura de oleo', 'oleo', 'combustible',

        'obsceno', 'inmoral', 'pornografia',

        'patrimonio cultural', 'pieza arqueologica', 'huaco', 'bien cultural',

        'marfil', 'vicuna', 'tortuga', 'animal silvestre', 'especie protegida', 'caparazon de tortuga',

        'harina de pescado',
    ];

    public const PELIGROSOS = [

        'perfume', 'perfumes', 'colonia', 'colonias', 'aerosol', 'aerosoles', 'spray',
        'pintura en spray', 'laca', 'alcohol', 'agua oxigenada', 'oxigenada', 'acetona',
        'azufre', 'hielo seco', 'gaseosa', 'bebida gaseosa', 'gas', 'butano',
        'magnetizado', 'iman', 'radioactivo', 'infeccioso', 'bebida alcoholica', 'licor', 'cerveza', 'vino',

        'pila de litio', 'bateria de litio', 'pila', 'pilas', 'bateria', 'baterias', 'powerbank', 'power bank',

        'arma', 'arma de fuego', 'pistola', 'revolver', 'fusil', 'municion', 'municiones',
        'cartucho', 'bala', 'polvora', 'explosivo', 'fuego artificial', 'pirotecnico', 'petardo',
        'bengala', 'cohete', 'cuchillo', 'machete', 'punzocortante',

        'perecible', 'perecibles', 'insecto', 'insectos', 'animal vivo',
    ];

    public const RESTRINGIDOS = [

        'medicina', 'medicamento', 'pastilla', 'pastillas', 'remedio', 'jarabe', 'antibiotico',
        'antigripal', 'paracetamol', 'ibuprofeno', 'aspirina', 'amoxicilina', 'omeprazol',
        'naproxeno', 'capsula', 'comprimido', 'vitamina', 'suplemento', 'ampolla', 'insulina',

        'joya', 'joyas', 'oro', 'plata', 'anillo', 'collar', 'pulsera', 'arete', 'aretes',
        'cadena de oro', 'lingote', 'joyeria', 'oro en bruto',

        'semilla', 'semillas', 'planta', 'plantas', 'alimento fresco', 'fruta', 'frutas',
        'verdura', 'verduras', 'carne', 'queso', 'lacteo', 'polen', 'grano crudo', 'grano verde',
        'cafe verde', 'cacao en grano', 'flor natural', 'plancton',

        'madera', 'una de gato', 'pluma', 'plumas', 'cuero exotico', 'concha', 'hueso de animal',
        'orquidea', 'cactus', 'semilla forestal', 'caparazon', 'artesania con plumas',

        'arte antiguo', 'antiguedad', 'antiguedades', 'reliquia', 'ceramica antigua',
        'cuadro antiguo', 'moneda antigua', 'replica de arte', 'replica', 'pintura antigua',
    ];

    public const ENTIDAD_POR_PALABRA = [

        'medicina' => 'DIGEMID', 'medicamento' => 'DIGEMID', 'pastilla' => 'DIGEMID',
        'remedio' => 'DIGEMID', 'jarabe' => 'DIGEMID', 'paracetamol' => 'DIGEMID',
        'ibuprofeno' => 'DIGEMID', 'amoxicilina' => 'DIGEMID', 'antibiotico' => 'DIGEMID',

        'joya' => 'SUNAT', 'joyas' => 'SUNAT', 'oro' => 'SUNAT', 'plata' => 'SUNAT',
        'anillo' => 'SUNAT', 'collar' => 'SUNAT', 'joyeria' => 'SUNAT',

        'semilla' => 'SENASA', 'semillas' => 'SENASA', 'planta' => 'SENASA', 'plantas' => 'SENASA',
        'fruta' => 'SENASA', 'carne' => 'SENASA', 'queso' => 'SENASA', 'grano crudo' => 'SENASA',

        'madera' => 'SERFOR', 'una de gato' => 'SERFOR', 'pluma' => 'SERFOR', 'plumas' => 'SERFOR',
        'orquidea' => 'SERFOR',

        'arte antiguo' => 'Ministerio de Cultura', 'antiguedad' => 'Ministerio de Cultura',
        'reliquia' => 'Ministerio de Cultura', 'moneda antigua' => 'Ministerio de Cultura',
        'replica' => 'Ministerio de Cultura',
    ];

    public const DOCUMENTO_POR_ENTIDAD = [
        'DIGEMID' => 'Registro sanitario DIGEMID (+ receta médica vigente)',
        'SENASA' => 'Certificado fitosanitario / zoosanitario (SENASA)',
        'SERFOR' => 'Autorización SERFOR / permiso CITES',
        'SUNAT' => 'Declaración de salida por Exporta Fácil (SUNAT)',
        'Ministerio de Cultura' => 'Certificado de no ser patrimonio cultural (Min. Cultura)',
    ];
}
