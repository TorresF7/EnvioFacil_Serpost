<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsEventsSeeder extends Seeder
{
    private const SESIONES = 420;

    private const PASOS = ['producto', 'destino', 'contenido', 'remitente', 'servicio', 'revisa'];

    private const PROB_COMPLETA = [0.95, 0.88, 0.72, 0.70, 0.92, 0.94];

    private const CAMPOS = [
        'producto' => ['articulo_descripcion'],
        'destino' => ['nombres_y_apellidos', 'direccion', 'ciudad', 'codigo_postal', 'pais', 'destinatario_email'],
        'contenido' => ['articulo_cantidad', 'articulo_peso', 'articulo_valor', 'articulo_pais_origen'],
        'remitente' => ['remitente_dni', 'nombres_y_apellidos', 'direccion', 'ciudad', 'telefono', 'remitente_email'],
        'servicio' => ['peso_bruto', 'largo_cm', 'ancho_cm', 'alto_cm', 'oficina'],
        'revisa' => [],
    ];

    private const TERMINOS = [
        'verde' => ['polo', 'chompa', 'cafe', 'libro', 'ropa', 'artesania', 'miel', 'quinua'],
        'ambar' => ['medicina', 'oro', 'semilla', 'madera', 'pintura'],
        'rojo' => ['perfume', 'bateria', 'dinero', 'arma', 'aerosol'],
        'desconocido' => ['cuy', 'tela shipibo', 'chuspa', 'retablo', 'mate burilado', 'tumbo', 'aguaymanto'],
    ];

    private Carbon $ahora;

    public function run(): void
    {
        mt_srand(20260613);
        DB::table('analytics_events')->delete();

        $this->ahora = now();
        $filas = [];

        for ($i = 0; $i < self::SESIONES; $i++) {
            foreach ($this->sesion($i) as $fila) {
                $filas[] = $fila;
            }
            if (count($filas) >= 1000) {
                DB::table('analytics_events')->insert($filas);
                $filas = [];
            }
        }

        if ($filas !== []) {
            DB::table('analytics_events')->insert($filas);
        }

        $this->command?->info('Sembrados '.DB::table('analytics_events')->count().' eventos de analítica simulada.');
    }

    private function sesion(int $i): array
    {
        $sid = sprintf('seed-%04d-%s', $i, substr(md5((string) $i), 0, 8));
        $device = mt_rand(1, 100) <= 60 ? 'mobile' : 'desktop';
        $lang = $this->elegirIdioma();

        $reloj = $this->ahora->copy()
            ->subDays(mt_rand(0, 29))
            ->setTime(mt_rand(7, 21), mt_rand(0, 59), mt_rand(0, 59));
        $inicio = $reloj->copy();

        $eventos = [];

        if (mt_rand(1, 100) <= 16) {
            $feature = ['aplus', 'contrast', 'audio', 'voice'][mt_rand(0, 3)];
            $eventos[] = $this->fila($sid, $this->avanzar($reloj), 'a11y_toggle', ['feature' => $feature]);
        }
        if ($lang !== 'es' || mt_rand(1, 100) <= 8) {
            $eventos[] = $this->fila($sid, $this->avanzar($reloj), 'lang_change', ['lang' => $lang]);
        }

        $completoTodo = true;

        foreach (self::PASOS as $idx => $paso) {
            $eventos[] = $this->fila($sid, $this->avanzar($reloj), 'step_enter', ['step_id' => $paso]);

            if ($paso === 'producto') {
                foreach (range(1, mt_rand(1, 3)) as $_) {
                    $eventos[] = $this->verifier($sid, $this->avanzar($reloj));
                }
            }

            $ultimoCampo = null;
            foreach ($this->camposTocados($paso) as $campo) {
                $ultimoCampo = $campo;
                $eventos[] = $this->fila($sid, $this->avanzar($reloj), 'field_focus', ['step_id' => $paso, 'field_id' => $campo]);
                $eventos[] = $this->fila($sid, $this->avanzar($reloj), 'field_blur', [
                    'step_id' => $paso,
                    'field_id' => $campo,
                    'duration_ms' => mt_rand(1500, 16000),
                ]);
                if ($error = $this->errorDe($campo)) {
                    $eventos[] = $this->fila($sid, $this->avanzar($reloj), 'field_error', [
                        'step_id' => $paso,
                        'field_id' => $campo,
                        'error_code' => $error,
                    ]);
                }
            }

            if ((mt_rand(1, 1000) / 1000) <= self::PROB_COMPLETA[$idx]) {
                $eventos[] = $this->fila($sid, $this->avanzar($reloj), 'step_complete', [
                    'step_id' => $paso,
                    'duration_ms' => mt_rand(25000, 160000),
                ]);
            } else {

                $eventos[] = $this->fila($sid, $this->avanzar($reloj), 'step_abandon', [
                    'step_id' => $paso,
                    'field_id' => $ultimoCampo ?? $this->campoAbandono($paso),
                ]);
                $completoTodo = false;
                break;
            }
        }

        if ($completoTodo) {
            $eventos[] = $this->fila($sid, $this->avanzar($reloj), 'step_enter', ['step_id' => 'qr']);
            $eventos[] = $this->fila($sid, $this->avanzar($reloj), 'completion', [
                'duration_ms' => $inicio->diffInMilliseconds($reloj),
                'device' => $device,
            ]);
        }

        return $eventos;
    }

    private function avanzar(Carbon $reloj): Carbon
    {

        $reloj->addSeconds(mt_rand(2, 15));

        return $reloj->copy();
    }

    private function camposTocados(string $paso): array
    {
        $campos = self::CAMPOS[$paso];
        if ($campos === []) {
            return [];
        }
        shuffle($campos);

        return array_slice($campos, 0, mt_rand(1, count($campos)));
    }

    private function campoAbandono(string $paso): ?string
    {
        $campos = self::CAMPOS[$paso];

        return $campos === [] ? null : $campos[0];
    }

    private function errorDe(string $campo): ?string
    {
        if (mt_rand(1, 100) > 22) {
            return null;
        }

        return match (true) {
            $campo === 'remitente_dni' => 'documento_invalido',
            str_contains($campo, 'email') => 'email_invalido',
            $campo === 'articulo_descripcion' => mt_rand(0, 1) ? 'descripcion_vaga' : 'articulo_bloqueado',
            default => 'requerido',
        };
    }

    private function verifier(string $sid, Carbon $ts): array
    {
        $r = mt_rand(1, 100);
        [$cat, $verdict, $grupo, $review] = match (true) {
            $r <= 55 => ['permitido', 'verde', 'verde', 0],
            $r <= 80 => ['restringido', 'ambar', 'ambar', 0],
            $r <= 92 => [mt_rand(0, 1) ? 'prohibido' : 'peligroso', 'rojo', 'rojo', 0],
            default => ['desconocido', 'desconocido', 'desconocido', 1],
        };
        $term = self::TERMINOS[$grupo][array_rand(self::TERMINOS[$grupo])];

        return $this->fila($sid, $ts, 'verifier_query', [
            'step_id' => 'producto',
            'resolved_category' => $cat,
            'verdict' => $verdict,
            'term' => $term,
            'needs_review' => $review,
        ]);
    }

    private function elegirIdioma(): string
    {
        $r = mt_rand(1, 100);

        return $r <= 86 ? 'es' : ($r <= 95 ? 'qu' : 'en');
    }

    private function fila(string $sid, Carbon $ts, string $type, array $extra = []): array
    {
        return array_merge([
            'session_id' => $sid,
            'type' => $type,
            'step_id' => null,
            'field_id' => null,
            'duration_ms' => null,
            'resolved_category' => null,
            'verdict' => null,
            'error_code' => null,
            'device' => null,
            'lang' => null,
            'feature' => null,
            'term' => null,
            'needs_review' => 0,
            'meta' => null,
            'occurred_at' => $ts->toDateTimeString(),
            'created_at' => $this->ahora->toDateTimeString(),
        ], $extra);
    }
}
