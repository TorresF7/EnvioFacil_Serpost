@php
    $form_code = $form['formulario'];
    $apaisado = $form_code !== 'CN22';
    $partial = match ($form_code) {
        'CN22' => 'formulario.partials.cn22',
        'CN23 EMS' => 'formulario.partials.cn23',
        default => 'formulario.partials.cp72',
    };
    $sym = ['USD' => 'US$', 'EUR' => '€', 'PEN' => 'S/', 'GBP' => '£', 'JPY' => '¥', 'BRL' => 'R$'][$form['divisa']] ?? $form['divisa'];
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>{{ $form_code }} · {{ $form['codigo'] }}</title>
    <style>
        @page { margin: 8mm; }
        * { box-sizing: border-box; }
        body { font-family: DejaVu Sans, Arial, sans-serif; color: #000; font-size: 9px; line-height: 1.25; margin: 0; }
        table { border-collapse: collapse; width: 100%; }
        td, th { vertical-align: top; padding: 2px 4px; }
        .b td, .b th, td.b, th.b { border: 0.75px solid #000; }
        .grid td { border: 0.75px solid #000; }

        .lbl { font-size: 7px; color: #222; display: block; }
        .val { font-weight: bold; font-size: 10px; }
        .hd { background: #e8e8e8; font-weight: bold; font-size: 7.5px; text-align: left; }
        .cen { text-align: center; }
        .der { text-align: right; }
        .muted { color: #444; font-size: 8px; }
        .jura { font-size: 7px; font-style: italic; padding: 4px 5px; }
        .doc { border: 1.5px solid #000; }
        .azul { color: #1b3c8c; }
        .naranja { color: #e2660f; }
        .rojo { color: #d11f2d; }
        .bg-azul { background: #1b3c8c; color: #fff; }
        .bg-naranja { background: #e2660f; color: #fff; }
        .bg-verde { background: #1f7a3d; color: #fff; }
        .bg-gris { background: #555; color: #fff; }
        .bg-tint { background: #eef2fb; }
        .firma { height: 26px; }
    </style>
</head>
<body>
    @include($partial, ['form' => $form, 'sym' => $sym])
</body>
</html>
