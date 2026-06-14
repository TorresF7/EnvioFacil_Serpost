<?php

namespace App\Data;

final class PartidasArancelarias
{

    public static function todas(): array
    {
        return [
            ['codigo' => '6109.10', 'descripcion' => 'T-shirts y camisetas de algodón, de punto', 'keywords' => ['polo', 'camiseta', 't-shirt', 'tshirt', 'algodon', 'remera']],
            ['codigo' => '6110.20', 'descripcion' => 'Suéteres, jerséis y pulóveres de algodón', 'keywords' => ['chompa', 'sueter', 'jersey', 'pulover', 'chaleco', 'sweater']],
            ['codigo' => '6105.10', 'descripcion' => 'Camisas de algodón para hombre, de punto', 'keywords' => ['camisa', 'blusa', 'hombre']],
            ['codigo' => '6203.42', 'descripcion' => 'Pantalones de algodón para hombre', 'keywords' => ['pantalon', 'jean', 'jeans', 'short', 'bermuda']],
            ['codigo' => '6204.62', 'descripcion' => 'Pantalones de algodón para mujer', 'keywords' => ['pantalon mujer', 'falda', 'vestido']],
            ['codigo' => '6217.10', 'descripcion' => 'Complementos de vestir (gorros, guantes, bufandas)', 'keywords' => ['gorro', 'chullo', 'guante', 'bufanda', 'chalina', 'accesorio ropa']],
            ['codigo' => '6403.99', 'descripcion' => 'Calzado con suela de caucho y parte superior de cuero', 'keywords' => ['zapato', 'calzado', 'zapatilla', 'bota', 'sandalia']],
            ['codigo' => '4202.22', 'descripcion' => 'Bolsos de mano con superficie de materia textil', 'keywords' => ['bolso', 'cartera', 'mochila', 'maletin', 'morral']],
            ['codigo' => '5805.00', 'descripcion' => 'Tapicería y tejidos artesanales (tapices)', 'keywords' => ['tapiz', 'manta', 'frazada', 'textil artesanal', 'telar']],
            ['codigo' => '6217.90', 'descripcion' => 'Artesanías textiles diversas', 'keywords' => ['artesania textil', 'bordado', 'tejido a mano']],
            ['codigo' => '0901.21', 'descripcion' => 'Café tostado, sin descafeinar', 'keywords' => ['cafe', 'cafe tostado', 'grano de cafe']],
            ['codigo' => '1801.00', 'descripcion' => 'Cacao en grano, entero o partido', 'keywords' => ['cacao', 'grano de cacao']],
            ['codigo' => '1806.31', 'descripcion' => 'Chocolate relleno, en bloques o barras', 'keywords' => ['chocolate', 'bombon', 'cacao chocolate', 'tableta chocolate']],
            ['codigo' => '2106.90', 'descripcion' => 'Preparaciones alimenticias diversas (snacks, suplementos)', 'keywords' => ['snack', 'suplemento', 'maca', 'galleta', 'dulce', 'alimento']],
            ['codigo' => '0910.99', 'descripcion' => 'Especias y condimentos (ají, hierbas secas)', 'keywords' => ['aji', 'especia', 'condimento', 'hierba', 'oregano', 'comino']],
            ['codigo' => '4901.99', 'descripcion' => 'Libros, folletos e impresos similares', 'keywords' => ['libro', 'folleto', 'revista', 'cuaderno impreso', 'manual']],
            ['codigo' => '9503.00', 'descripcion' => 'Juguetes y modelos reducidos', 'keywords' => ['juguete', 'muñeca', 'peluche', 'juego', 'figura']],
            ['codigo' => '7113.19', 'descripcion' => 'Artículos de joyería de metales preciosos', 'keywords' => ['joya', 'joyas', 'anillo', 'collar', 'arete', 'pulsera', 'oro', 'plata']],
            ['codigo' => '7117.19', 'descripcion' => 'Bisutería de metal común', 'keywords' => ['bisuteria', 'fantasia', 'aretes fantasia', 'collar bisuteria']],
            ['codigo' => '4203.10', 'descripcion' => 'Prendas de vestir de cuero', 'keywords' => ['cuero', 'casaca cuero', 'chaqueta cuero', 'prenda cuero']],
            ['codigo' => '4205.00', 'descripcion' => 'Manufacturas de cuero (artesanía, billeteras)', 'keywords' => ['billetera cuero', 'correa', 'cinturon', 'articulo cuero']],
            ['codigo' => '6913.90', 'descripcion' => 'Estatuillas y artículos de cerámica para adorno', 'keywords' => ['ceramica', 'estatuilla', 'adorno', 'vasija', 'huaco', 'figura ceramica']],
            ['codigo' => '4419.00', 'descripcion' => 'Artículos de madera para mesa o cocina', 'keywords' => ['madera', 'utensilio madera', 'tabla', 'cuchara madera', 'artesania madera']],
            ['codigo' => '7013.49', 'descripcion' => 'Artículos de vidrio para mesa o cocina', 'keywords' => ['vidrio', 'vaso', 'copa', 'articulo vidrio']],
            ['codigo' => '3304.99', 'descripcion' => 'Preparaciones de belleza y maquillaje', 'keywords' => ['cosmetico', 'maquillaje', 'crema', 'labial', 'belleza']],
            ['codigo' => '3304.10', 'descripcion' => 'Preparaciones para el cuidado de la piel', 'keywords' => ['crema piel', 'locion', 'cuidado piel', 'serum']],
            ['codigo' => '8517.13', 'descripcion' => 'Teléfonos inteligentes (smartphones)', 'keywords' => ['celular', 'smartphone', 'telefono movil', 'movil']],
            ['codigo' => '8471.30', 'descripcion' => 'Computadoras portátiles (laptops, tablets)', 'keywords' => ['laptop', 'computadora', 'tablet', 'notebook', 'pc portatil']],
            ['codigo' => '8518.30', 'descripcion' => 'Auriculares y audífonos', 'keywords' => ['audifono', 'auricular', 'headphone', 'earbud']],
            ['codigo' => '8504.40', 'descripcion' => 'Cargadores y convertidores eléctricos', 'keywords' => ['cargador', 'adaptador', 'fuente poder']],
            ['codigo' => '9102.11', 'descripcion' => 'Relojes de pulsera', 'keywords' => ['reloj', 'reloj pulsera', 'smartwatch']],
            ['codigo' => '4911.91', 'descripcion' => 'Estampas, grabados, fotografías', 'keywords' => ['cuadro', 'pintura', 'grabado', 'fotografia', 'lamina', 'arte']],
            ['codigo' => '9209.94', 'descripcion' => 'Partes y accesorios de instrumentos musicales', 'keywords' => ['instrumento musical', 'zampoña', 'quena', 'cajon', 'charango', 'flauta']],
            ['codigo' => '3926.40', 'descripcion' => 'Estatuillas y artículos de adorno de plástico', 'keywords' => ['plastico', 'souvenir', 'llavero', 'adorno plastico']],
            ['codigo' => '4602.19', 'descripcion' => 'Artículos de cestería (fibras vegetales)', 'keywords' => ['cesteria', 'canasta', 'sombrero paja', 'mate burilado', 'fibra vegetal', 'mimbre']],
            ['codigo' => '6802.99', 'descripcion' => 'Manufacturas de piedra trabajada', 'keywords' => ['piedra', 'escultura piedra', 'huamanga', 'talla piedra']],
            ['codigo' => '0904.21', 'descripcion' => 'Ají y pimientos secos', 'keywords' => ['aji seco', 'panca', 'rocoto seco', 'pimiento seco']],
            ['codigo' => '2005.99', 'descripcion' => 'Hortalizas preparadas o conservadas', 'keywords' => ['conserva', 'enlatado', 'hortaliza', 'aceituna']],
            ['codigo' => '3924.10', 'descripcion' => 'Vajilla y artículos de cocina de plástico', 'keywords' => ['vajilla', 'tupper', 'plato plastico', 'utensilio cocina']],
            ['codigo' => '9504.50', 'descripcion' => 'Videoconsolas y videojuegos', 'keywords' => ['consola', 'videojuego', 'videoconsola', 'gamepad', 'mando']],
        ];
    }
}
