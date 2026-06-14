<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 16mm; }
        * { box-sizing: border-box; }
        body { font-family: DejaVu Sans, Arial, sans-serif; color: #111; margin: 0; }
        .caja { border: 2px solid #111; padding: 26px 30px; }
        .titulo { font-weight: bold; font-size: 18px; letter-spacing: 0.5px; margin: 4px 0 6px; }
        table { width: 100%; border-collapse: collapse; }
        td.lab { font-weight: bold; font-size: 14px; white-space: nowrap; width: 1%; padding: 16px 8px 3px 0; vertical-align: bottom; }
        td.ln { border-bottom: 1px dotted #666; font-size: 15px; padding: 16px 4px 3px; vertical-align: bottom; }
        .gap { height: 30px; }
    </style>
</head>
<body>
    <div class="caja">

        <div class="titulo">REMITENTE:</div>
        <table>
            <tr>
                <td class="lab">NOMBRE:</td>
                <td class="ln" colspan="3">{{ $remitente->nombre_completo }}</td>
            </tr>
            <tr>
                <td class="lab">DIRECCION:</td>
                <td class="ln" colspan="3">{{ trim($remitente->direccion . ', ' . $remitente->ciudad . ($remitente->departamento ? ' - ' . $remitente->departamento : '') . ', Perú') }}</td>
            </tr>
            <tr>
                <td class="lab">CORREO ELECTRONICO:</td>
                <td class="ln" colspan="3">{{ $remitente->email }}</td>
            </tr>
            <tr>
                <td class="lab">TELEFONO:</td>
                <td class="ln" colspan="3">{{ $remitente->telefono }}</td>
            </tr>
        </table>

        <div class="gap"></div>

        <div class="titulo">DESTINATARIO:</div>
        <table>
            <tr>
                <td class="lab">NOMBRE:</td>
                <td class="ln" colspan="3">{{ $destinatario->nombre_completo }}</td>
            </tr>
            <tr>
                <td class="lab">DIRECCION:</td>
                <td class="ln" colspan="3">{{ trim($destinatario->direccion . ($destinatario->ciudad ? ', ' . $destinatario->ciudad : '') . ($destinatario->estado_region ? ' - ' . $destinatario->estado_region : '')) }}</td>
            </tr>
            <tr>
                <td class="lab">CODIGO POSTAL:</td>
                <td class="ln" style="width: 22%;">{{ $destinatario->codigo_postal }}</td>
                <td class="lab">PAIS:</td>
                <td class="ln">{{ $destinatario->pais }}</td>
            </tr>
            <tr>
                <td class="lab">CORREO ELECTRONICO:</td>
                <td class="ln" colspan="3">{{ $destinatario->email }}</td>
            </tr>
            <tr>
                <td class="lab">TELEFONO:</td>
                <td class="ln" colspan="3">{{ $destinatario->telefono }}</td>
            </tr>
        </table>
    </div>
</body>
</html>
