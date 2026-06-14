

export const COLOR_POR_ESTADO = {
    permitido: 'verde',
    restringido: 'ambar',
    peligroso: 'rojo',
    prohibido: 'rojo',
};

export const SEVERIDAD = { prohibido: 0, peligroso: 1, restringido: 2, permitido: 3 };

export const COLOR_VISUAL = {
    verde: { icono: 'circle-check', sello: 'Admitido', clase: 'text-go', fondo: 'bg-go-tint border-go/40' },
    ambar: { icono: 'triangle-alert', sello: 'Con permiso', clase: 'text-ambar', fondo: 'bg-ambar-tint border-ambar/40' },
    rojo: { icono: 'octagon-x', sello: 'No admitido', clase: 'text-stop', fondo: 'bg-stop-tint border-stop/50' },
};

export const CONTROLADOS = [
    'tramadol', 'tramal', 'diazepam', 'valium', 'clonazepam', 'rivotril', 'alprazolam',
    'lorazepam', 'zolpidem', 'morfina', 'fentanilo', 'fentanil', 'codeina', 'oxicodona',
    'metadona', 'fenobarbital', 'midazolam', 'petidina', 'ketamina',
];

export const CATEGORIAS = [

    {
        clave: 'medicamento_controlado',
        estado: 'prohibido',
        titulo: 'Medicamento controlado',
        entidad: 'DIGEMID',
        que_necesitas: 'Por seguridad, este tipo de medicamento no viaja por correo.',
        siguiente_paso: 'Consulta en la oficina SERPOST o llama gratis a la Línea 1812.',
        base_legal: 'D.S. 023-2001-SA (estupefacientes y psicotrópicos); Ley 29459. Convenio UPU art. 19.',
        alternativa: null,
        sinonimos: CONTROLADOS,
    },
    {
        clave: 'dinero',
        estado: 'prohibido',
        titulo: 'Dinero en efectivo',
        entidad: null,
        que_necesitas: 'El dinero, cheques y tarjetas no viajan dentro de una encomienda.',
        siguiente_paso: 'Para mandar dinero, usa Correo Giro de SERPOST.',
        base_legal: 'Convenio UPU art. 19; Directiva 003-G/21 Anexo 2 (objetos prohibidos).',
        alternativa: 'Envíalo con Correo Giro, el servicio de giros de SERPOST (rápido y seguro).',
        sinonimos: [
            'dinero en efectivo', 'efectivo', 'dinero', 'billete', 'billetes', 'papel moneda',
            'cheque', 'cheques', 'tarjeta de credito', 'valores al portador',
        ],
    },
    {
        clave: 'drogas',
        estado: 'prohibido',
        titulo: 'Drogas y estupefacientes',
        entidad: null,
        que_necesitas: 'Este contenido no se admite por vía postal.',
        siguiente_paso: 'Este contenido no se admite. Si es un error, consúltalo en oficina.',
        base_legal: 'Ley 28008 (Delitos Aduaneros); D.S. 023-2001-SA; Convenio UPU art. 19.',
        alternativa: null,
        sinonimos: ['droga', 'drogas', 'estupefaciente', 'sicotropico', 'psicotropico', 'cocaina', 'marihuana', 'cannabis'],
    },
    {
        clave: 'inflamables',
        estado: 'prohibido',
        titulo: 'Material inflamable',
        entidad: null,
        que_necesitas: 'Por seguridad no viaja por avión; mejor no lo incluyas.',
        siguiente_paso: 'Retíralo del envío para poder continuar.',
        base_legal: 'Mercancías peligrosas IATA/UPU; Directiva 003-G/21 Anexo 2.',
        alternativa: null,
        sinonimos: [
            'fosforo', 'fosforos', 'cerillo', 'cerillos', 'encendedor', 'encendedores',
            'gasolina', 'liquido inflamable', 'solido inflamable', 'pintura de oleo', 'oleo', 'combustible',
        ],
    },
    {
        clave: 'contenido_ilegal',
        estado: 'prohibido',
        titulo: 'Contenido no permitido',
        entidad: null,
        que_necesitas: 'Este tipo de contenido está prohibido por la normativa postal.',
        siguiente_paso: 'Este contenido no se admite por vía postal.',
        base_legal: 'Convenio UPU art. 19; Directiva 003-G/21 Anexo 2.',
        alternativa: null,
        sinonimos: ['obsceno', 'inmoral', 'pornografia'],
    },
    {
        clave: 'patrimonio',
        estado: 'prohibido',
        titulo: 'Patrimonio cultural',
        entidad: 'Ministerio de Cultura',
        que_necesitas: 'Los bienes que son patrimonio cultural de la Nación no pueden salir del país.',
        siguiente_paso: 'Si es una réplica, necesitas certificación del Ministerio de Cultura (ver "réplicas de arte").',
        base_legal: 'Ley 28296 (Patrimonio Cultural); Ley 28008. Ministerio de Cultura.',
        alternativa: null,
        sinonimos: ['patrimonio cultural', 'pieza arqueologica', 'huaco', 'bien cultural'],
    },
    {
        clave: 'especies_protegidas',
        estado: 'prohibido',
        titulo: 'Especie silvestre protegida',
        entidad: 'SERFOR',
        que_necesitas: 'La fauna y flora silvestre protegida no puede salir del país.',
        siguiente_paso: 'Consulta con SERFOR; bajo CITES está prohibida su exportación postal.',
        base_legal: 'Ley 29763 (Forestal y de Fauna Silvestre); CITES. SERFOR.',
        alternativa: null,
        sinonimos: ['marfil', 'vicuna', 'tortuga', 'animal silvestre', 'especie protegida', 'caparazon de tortuga'],
    },
    {
        clave: 'prohibido_exportacion',
        estado: 'prohibido',
        titulo: 'Producto restringido a la exportación',
        entidad: 'SUNAT',
        que_necesitas: 'Este producto tiene restricción de salida del país por vía postal.',
        siguiente_paso: 'Verifícalo por subpartida en SUNAT (Mercancías Restringidas y Prohibidas).',
        base_legal: 'SUNAT – Mercancías Restringidas y Prohibidas; Ley 28008.',
        alternativa: null,
        sinonimos: ['harina de pescado'],
    },

    {
        clave: 'mercancia_peligrosa',
        estado: 'peligroso',
        titulo: 'Mercancía peligrosa',
        entidad: null,
        que_necesitas: 'Son inflamables o a presión, así que no viajan por correo.',
        siguiente_paso: 'Retíralo del envío. ¿Dudas? Llama gratis a la Línea 1812.',
        base_legal: 'Mercancías peligrosas IATA/UPU; Directiva 003-G/21 Anexo 2.',
        alternativa: null,
        sinonimos: [
            'perfume', 'perfumes', 'colonia', 'colonias', 'aerosol', 'aerosoles', 'spray',
            'pintura en spray', 'laca', 'alcohol', 'agua oxigenada', 'oxigenada', 'acetona',
            'azufre', 'hielo seco', 'gaseosa', 'bebida gaseosa', 'gas', 'butano',
            'magnetizado', 'iman', 'radioactivo', 'infeccioso', 'bebida alcoholica', 'licor', 'cerveza', 'vino',
        ],
    },
    {
        clave: 'baterias',
        estado: 'peligroso',
        titulo: 'Pilas y baterías de litio',
        entidad: null,
        que_necesitas: 'Las baterías de litio sueltas no viajan por avión.',
        siguiente_paso: 'Envíalas dentro del aparato o consúltalo en oficina (Línea 1812).',
        base_legal: 'Mercancías peligrosas IATA/UPU; Directiva 003-G/21 Anexo 2.',
        alternativa: null,
        sinonimos: ['pila de litio', 'bateria de litio', 'pila', 'pilas', 'bateria', 'baterias', 'powerbank', 'power bank'],
    },
    {
        clave: 'armas',
        estado: 'peligroso',
        titulo: 'Armas, municiones y pirotecnia',
        entidad: 'SUCAMEC',
        que_necesitas: 'No viajan por correo; su control corresponde a la SUCAMEC.',
        siguiente_paso: 'Su control corresponde a la SUCAMEC; no se envían por correo.',
        base_legal: 'Ley 30299 (armas, municiones, pirotécnicos). SUCAMEC; mercancías peligrosas IATA/UPU.',
        alternativa: null,
        sinonimos: [
            'arma', 'arma de fuego', 'pistola', 'revolver', 'fusil', 'municion', 'municiones',
            'cartucho', 'bala', 'polvora', 'explosivo', 'fuego artificial', 'pirotecnico', 'petardo',
            'bengala', 'cohete', 'cuchillo', 'machete', 'punzocortante',
        ],
    },
    {
        clave: 'perecibles',
        estado: 'peligroso',
        titulo: 'Perecible o riesgoso',
        entidad: null,
        que_necesitas: 'Los productos frescos o seres vivos no viajan por correo.',
        siguiente_paso: 'Retíralo del envío. ¿Dudas? Llama gratis a la Línea 1812.',
        base_legal: 'Directiva 003-G/21 Anexo 2; mercancías peligrosas IATA/UPU.',
        alternativa: null,
        sinonimos: ['perecible', 'perecibles', 'insecto', 'insectos', 'animal vivo'],
    },

    {
        clave: 'medicina',
        estado: 'restringido',
        titulo: 'Medicina',
        entidad: 'DIGEMID',
        documento: 'Registro sanitario DIGEMID (+ receta médica vigente)',
        que_necesitas: 'Lleva la receta médica vigente y el empaque original.',
        siguiente_paso: 'Lo presentas el día de tu envío.',
        base_legal: 'Ley 29459 (productos farmacéuticos). DIGEMID. SUNAT – Mercancías Restringidas.',
        alternativa: null,
        sinonimos: [
            'medicina', 'medicamento', 'pastilla', 'pastillas', 'remedio', 'jarabe', 'antibiotico',
            'antigripal', 'paracetamol', 'ibuprofeno', 'aspirina', 'amoxicilina', 'omeprazol',
            'naproxeno', 'capsula', 'comprimido', 'vitamina', 'suplemento', 'ampolla', 'insulina',
        ],
    },
    {
        clave: 'joyas',
        estado: 'restringido',
        titulo: 'Joyas de oro o plata',
        entidad: 'SUNAT',
        documento: 'Declaración de salida por Exporta Fácil (SUNAT)',
        que_necesitas: 'Declara su valor real y usa un servicio asegurado.',
        siguiente_paso: 'Te orientan en la oficina.',
        base_legal: 'SUNAT (origen y trazabilidad); Directiva 003-G/21. Mercancías Restringidas.',
        alternativa: null,
        sinonimos: [
            'joya', 'joyas', 'oro', 'plata', 'anillo', 'collar', 'pulsera', 'arete', 'aretes',
            'cadena de oro', 'lingote', 'joyeria', 'oro en bruto',
        ],
    },
    {
        clave: 'agropecuario',
        estado: 'restringido',
        titulo: 'Semillas, plantas o alimentos frescos',
        entidad: 'SENASA',
        documento: 'Certificado fitosanitario / zoosanitario (SENASA)',
        que_necesitas: 'Necesitas el certificado del SENASA (fitosanitario o zoosanitario).',
        siguiente_paso: 'Lo tramitas en SENASA o te orientan en oficina.',
        base_legal: 'D.L. 1059 (Sanidad Agraria). SENASA. SUNAT – Mercancías Restringidas.',
        alternativa: null,
        sinonimos: [
            'semilla', 'semillas', 'planta', 'plantas', 'alimento fresco', 'fruta', 'frutas',
            'verdura', 'verduras', 'carne', 'queso', 'lacteo', 'polen', 'grano crudo', 'grano verde',
            'cafe verde', 'cacao en grano', 'flor natural', 'plancton',
        ],
    },
    {
        clave: 'fauna_flora',
        estado: 'restringido',
        titulo: 'Madera, fauna o flora',
        entidad: 'SERFOR',
        documento: 'Autorización SERFOR / permiso CITES',
        que_necesitas: 'Necesitas la autorización de SERFOR.',
        siguiente_paso: 'Lo tramitas con SERFOR.',
        base_legal: 'Ley 29763 (Forestal y de Fauna Silvestre); CITES. SERFOR.',
        alternativa: null,
        sinonimos: [
            'madera', 'una de gato', 'pluma', 'plumas', 'cuero exotico', 'concha', 'hueso de animal',
            'orquidea', 'cactus', 'semilla forestal', 'caparazon', 'artesania con plumas',
        ],
    },
    {
        clave: 'cultura',
        estado: 'restringido',
        titulo: 'Arte, antigüedades o réplicas',
        entidad: 'Ministerio de Cultura',
        documento: 'Certificado de no ser patrimonio cultural (Min. Cultura)',
        que_necesitas: 'Necesitas un certificado del Ministerio de Cultura.',
        siguiente_paso: 'Lo tramitas en el Ministerio de Cultura.',
        base_legal: 'Ley 28296 (Patrimonio Cultural). Ministerio de Cultura.',
        alternativa: null,
        sinonimos: [
            'arte antiguo', 'antiguedad', 'antiguedades', 'reliquia', 'ceramica antigua',
            'cuadro antiguo', 'moneda antigua', 'replica de arte', 'replica', 'pintura antigua',
        ],
    },

    {
        clave: 'ropa',
        estado: 'permitido',
        titulo: 'Ropa y textiles',
        entidad: null,
        que_necesitas: 'No figura en las listas de productos restringidos ni prohibidos.',
        siguiente_paso: 'Sigue completando tu declaración.',
        base_legal: null,
        alternativa: null,
        sinonimos: [
            'ropa', 'polo', 'polos', 'camisa', 'camiseta', 'pantalon', 'vestido', 'chompa', 'chaleco',
            'casaca', 'tejido', 'textil', 'tela', 'lana', 'alpaca', 'algodon', 'bufanda', 'gorro',
            'guante', 'guantes', 'media', 'medias', 'zapato', 'zapatilla', 'cartera', 'mochila', 'manta',
        ],
    },
    {
        clave: 'artesania',
        estado: 'permitido',
        titulo: 'Artesanías',
        entidad: null,
        que_necesitas: 'No figura en las listas de productos restringidos ni prohibidos.',
        siguiente_paso: 'Sigue completando tu declaración.',
        base_legal: null,
        alternativa: null,
        sinonimos: [
            'artesania', 'ceramica', 'arcilla', 'manualidad', 'adorno', 'recuerdo', 'souvenir',
            'llavero', 'bisuteria', 'retablo', 'mate burilado', 'tallado',
        ],
    },
    {
        clave: 'libros',
        estado: 'permitido',
        titulo: 'Libros y documentos',
        entidad: null,
        que_necesitas: 'No figura en las listas de productos restringidos ni prohibidos.',
        siguiente_paso: 'Sigue completando tu declaración.',
        base_legal: null,
        alternativa: null,
        sinonimos: [
            'libro', 'libros', 'libreta', 'cuaderno', 'documento', 'carta', 'foto', 'fotografia',
            'poster', 'cuadro', 'revista', 'agenda', 'impreso',
        ],
    },
    {
        clave: 'juguetes',
        estado: 'permitido',
        titulo: 'Juguetes (sin pilas)',
        entidad: null,
        que_necesitas: 'No figura en las listas, siempre que no lleve pilas ni baterías.',
        siguiente_paso: 'Sigue completando tu declaración.',
        base_legal: null,
        alternativa: null,
        sinonimos: ['juguete', 'juguetes', 'peluche', 'muneco', 'muneca', 'rompecabezas'],
    },
    {
        clave: 'alimentos_procesados',
        estado: 'permitido',
        titulo: 'Café, cacao y procesados',
        entidad: null,
        que_necesitas: 'Café, cacao y chocolate procesados no necesitan permiso.',
        siguiente_paso: 'Sigue completando tu declaración. (El grano crudo sí requiere SENASA.)',
        base_legal: null,
        alternativa: null,
        sinonimos: [
            'cafe', 'cafe tostado', 'cacao', 'chocolate', 'dulce', 'galleta', 'caramelo', 'miel',
            'quinua', 'quinoa', 'mermelada', 'panela', 'cereal',
        ],
    },
];

export const BASE_LEGAL_GENERAL = [
    'Directiva N° 003-G/21 (Anexos 1 y 2) y Convenio UPU art. 19.',
    'SUNAT – Mercancías Restringidas y Prohibidas (clasificación por subpartida).',
    'VUCE – Ventanilla Única de Comercio Exterior (D.S. 010-2010-MINCETUR).',
    'DIGEMID – Ley 29459 / D.S. 023-2001-SA (medicamentos y controlados).',
    'SENASA – sanidad agraria (cert. fitosanitario / zoosanitario).',
    'SERFOR / CITES – fauna y flora silvestre, madera.',
    'Ministerio de Cultura – patrimonio cultural (Ley 28296).',
    'SUCAMEC – armas, municiones y pirotécnicos (Ley 30299).',
    'Mercancías peligrosas IATA / UPU.',
    'Ley 28008 – Delitos Aduaneros.',
];

function sinonimosPorEstado(estado) {
    return CATEGORIAS.filter((c) => c.estado === estado).flatMap((c) => c.sinonimos);
}

export const PROHIBIDOS = [...sinonimosPorEstado('prohibido')];
export const PELIGROSOS = [...sinonimosPorEstado('peligroso')];
export const RESTRINGIDOS = [...sinonimosPorEstado('restringido')];
export const PERMITIDOS_CONOCIDOS = [...sinonimosPorEstado('permitido')];

export const DICCIONARIO = CATEGORIAS.flatMap((c) =>
    c.sinonimos.map((termino) => ({ termino, categoria: c })),
);

export const CATEGORIA_POR_CLAVE = Object.fromEntries(CATEGORIAS.map((c) => [c.clave, c]));
