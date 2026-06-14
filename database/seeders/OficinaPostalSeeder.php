<?php

namespace Database\Seeders;

use App\Models\OficinaPostal;
use Illuminate\Database\Seeder;

class OficinaPostalSeeder extends Seeder
{
    public function run(): void
    {
        $oficinas = [
            ['codigo' => 'TVALLE', 'nombre' => 'Tomás Valle', 'direccion' => 'Av. Tomás Valle 1234, Los Olivos', 'distrito' => 'Los Olivos', 'ciudad' => 'Lima', 'latitud' => -12.0177, 'longitud' => -77.0598, 'telefono' => '(01) 511-5000', 'horario' => 'Lun-Vie 8:30-18:00', 'es_principal' => true],
            ['codigo' => 'SISIDRO', 'nombre' => 'San Isidro', 'direccion' => 'Av. Petit Thouars 5000, San Isidro', 'distrito' => 'San Isidro', 'ciudad' => 'Lima', 'latitud' => -12.0972, 'longitud' => -77.0365, 'telefono' => '(01) 511-5010', 'horario' => 'Lun-Vie 8:30-18:00', 'es_principal' => false],
            ['codigo' => 'MIRAFLORES', 'nombre' => 'Miraflores', 'direccion' => 'Av. Petit Thouars 100, Miraflores', 'distrito' => 'Miraflores', 'ciudad' => 'Lima', 'latitud' => -12.1211, 'longitud' => -77.0297, 'telefono' => '(01) 511-5020', 'horario' => 'Lun-Vie 8:30-18:00', 'es_principal' => false],
            ['codigo' => 'CERCADO', 'nombre' => 'Cercado de Lima', 'direccion' => 'Jr. Conde de Superunda 170, Cercado', 'distrito' => 'Lima', 'ciudad' => 'Lima', 'latitud' => -12.0464, 'longitud' => -77.0428, 'telefono' => '(01) 511-5030', 'horario' => 'Lun-Vie 8:30-18:00', 'es_principal' => false],
            ['codigo' => 'SBORJA', 'nombre' => 'San Borja', 'direccion' => 'Av. San Borja Norte 1200, San Borja', 'distrito' => 'San Borja', 'ciudad' => 'Lima', 'latitud' => -12.1083, 'longitud' => -76.9986, 'telefono' => '(01) 511-5040', 'horario' => 'Lun-Vie 8:30-18:00', 'es_principal' => false],
            ['codigo' => 'AREQUIPA', 'nombre' => 'Arequipa', 'direccion' => 'Calle Moral 118, Cercado', 'distrito' => 'Arequipa', 'ciudad' => 'Arequipa', 'latitud' => -16.4090, 'longitud' => -71.5375, 'telefono' => '(054) 215-000', 'horario' => 'Lun-Vie 8:30-17:30', 'es_principal' => false],
            ['codigo' => 'TRUJILLO', 'nombre' => 'Trujillo', 'direccion' => 'Jr. Independencia 286, Trujillo', 'distrito' => 'Trujillo', 'ciudad' => 'Trujillo', 'latitud' => -8.1116, 'longitud' => -79.0288, 'telefono' => '(044) 245-000', 'horario' => 'Lun-Vie 8:30-17:30', 'es_principal' => false],
            ['codigo' => 'CHICLAYO', 'nombre' => 'Chiclayo', 'direccion' => 'Av. Elías Aguirre 140, Chiclayo', 'distrito' => 'Chiclayo', 'ciudad' => 'Chiclayo', 'latitud' => -6.7711, 'longitud' => -79.8409, 'telefono' => '(074) 235-000', 'horario' => 'Lun-Vie 8:30-17:30', 'es_principal' => false],
            ['codigo' => 'CUSCO', 'nombre' => 'Cusco', 'direccion' => 'Av. El Sol 800, Cusco', 'distrito' => 'Cusco', 'ciudad' => 'Cusco', 'latitud' => -13.5319, 'longitud' => -71.9675, 'telefono' => '(084) 225-000', 'horario' => 'Lun-Vie 8:30-17:30', 'es_principal' => false],
            ['codigo' => 'PIURA', 'nombre' => 'Piura', 'direccion' => 'Calle Libertad 482, Piura', 'distrito' => 'Piura', 'ciudad' => 'Piura', 'latitud' => -5.1945, 'longitud' => -80.6328, 'telefono' => '(073) 305-000', 'horario' => 'Lun-Vie 8:30-17:30', 'es_principal' => false],
        ];

        foreach ($oficinas as $oficina) {
            OficinaPostal::updateOrCreate(['codigo' => $oficina['codigo']], $oficina);
        }
    }
}
