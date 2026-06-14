<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class StoreAnalyticsEventsRequest extends FormRequest
{

    public const TIPOS = [
        'step_enter', 'step_complete', 'step_abandon', 'field_focus', 'field_blur',
        'field_error', 'verifier_query', 'back_nav', 'lang_change', 'a11y_toggle', 'completion',
    ];

    private const VERDICTS = ['verde', 'ambar', 'rojo', 'desconocido'];
    private const DEVICES = ['mobile', 'desktop'];
    private const LANGS = ['es', 'qu', 'en'];
    private const FEATURES = ['aplus', 'contrast', 'audio', 'voice'];
    private const CATEGORIAS = ['permitido', 'restringido', 'prohibido', 'peligroso', 'desconocido', 'vacio'];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {

        return [
            'events' => ['required', 'array', 'max:200'],
        ];
    }

    public function eventosLimpios(): array
    {
        $ahora = now();
        $marcaServidor = $ahora->toDateTimeString();
        $limpios = [];

        foreach ((array) $this->input('events', []) as $ev) {
            if (! is_array($ev)) {
                continue;
            }

            $type = $this->texto($ev['type'] ?? null, 40);
            if (! in_array($type, self::TIPOS, true)) {
                continue;
            }

            $sessionId = $this->texto($ev['session_id'] ?? null, 64);
            if ($sessionId === null || ! preg_match('/^[A-Za-z0-9\-]{1,64}$/', $sessionId)) {
                continue;
            }

            $meta = is_array($ev['meta'] ?? null) ? $ev['meta'] : [];

            $limpios[] = [
                'session_id' => $sessionId,
                'type' => $type,
                'step_id' => $this->slug($ev['step_id'] ?? null),
                'field_id' => $this->slug($ev['field_id'] ?? null),
                'duration_ms' => $this->entero($ev['duration_ms'] ?? null),
                'resolved_category' => $this->enLista($meta['resolved_category'] ?? null, self::CATEGORIAS),
                'verdict' => $this->enLista($meta['verdict'] ?? null, self::VERDICTS),
                'error_code' => $this->slug($meta['error_code'] ?? null),
                'device' => $this->enLista($meta['device'] ?? null, self::DEVICES),
                'lang' => $this->enLista($meta['lang'] ?? null, self::LANGS),
                'feature' => $this->enLista($meta['feature'] ?? null, self::FEATURES),
                'term' => $this->termino($meta['term'] ?? null),
                'needs_review' => (int) (bool) ($meta['needs_review'] ?? false),
                'meta' => null,
                'occurred_at' => $this->fecha($ev['ts'] ?? null, $ahora),
                'created_at' => $marcaServidor,
            ];
        }

        return $limpios;
    }

    private function texto($valor, int $max): ?string
    {
        if (! is_string($valor) && ! is_numeric($valor)) {
            return null;
        }
        $valor = trim((string) $valor);

        return $valor === '' ? null : mb_substr($valor, 0, $max);
    }

    private function slug($valor): ?string
    {
        $valor = $this->texto($valor, 64);
        if ($valor === null) {
            return null;
        }
        $valor = strtolower($valor);

        return preg_match('/^[a-z0-9_\-]{1,64}$/', $valor) ? $valor : null;
    }

    private function enLista($valor, array $lista): ?string
    {
        $valor = is_string($valor) ? strtolower(trim($valor)) : null;

        return in_array($valor, $lista, true) ? $valor : null;
    }

    private function entero($valor): ?int
    {
        if (! is_numeric($valor)) {
            return null;
        }
        $n = (int) $valor;

        return ($n >= 0 && $n <= 86_400_000) ? $n : null;
    }

    private function termino($valor): ?string
    {
        if (! is_string($valor)) {
            return null;
        }
        $valor = trim($valor);
        if ($valor === '' || mb_strlen($valor) > 60) {
            return null;
        }
        if (str_contains($valor, '@')) {
            return null;
        }
        if (preg_match('/\d{8,}/', $valor)) {
            return null;
        }
        if (preg_match('/[\w.+-]+@[\w.-]+\.\w+/', $valor)) {
            return null;
        }

        return mb_substr($valor, 0, 60);
    }

    private function fecha($ts, Carbon $ahora): string
    {

        if (is_numeric($ts)) {
            $seg = (int) ($ts / 1000);
            if ($seg > 1_600_000_000 && $seg < ($ahora->timestamp + 3600)) {
                return Carbon::createFromTimestamp($seg)->toDateTimeString();
            }
        }

        return $ahora->toDateTimeString();
    }
}
