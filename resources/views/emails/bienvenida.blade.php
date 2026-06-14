<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido a SERPOST Envío Fácil</title>
</head>
<body style="margin:0;padding:0;background:#f1f5f9;font-family:Arial,Helvetica,sans-serif;color:#1f2937;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9;padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:12px;overflow:hidden;border:1px solid #e2e8f0;">
                    <tr>
                        <td style="background:#1b3c8c;padding:24px 28px;">
                            <span style="font-size:22px;font-weight:bold;color:#ffffff;letter-spacing:-0.5px;">Serpost</span>
                            <span style="font-size:13px;color:#cbd5e1;"> · El Correo del Perú</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:28px;">
                            <h1 style="margin:0 0 12px;font-size:20px;color:#0f172a;">¡Hola, {{ $usuario->name }}!</h1>
                            <p style="margin:0 0 14px;font-size:15px;line-height:1.6;color:#334155;">
                                Tu cuenta en <strong>SERPOST Envío Fácil</strong> fue creada con éxito. Ya puedes
                                preparar tus envíos internacionales en línea, generar tu código QR y llevar el
                                rótulo y la declaración aduanera ya listos para ahorrar tiempo en ventanilla.
                            </p>
                            <table role="presentation" cellpadding="0" cellspacing="0" style="margin:18px 0;">
                                <tr>
                                    <td style="border-radius:8px;background:#1b3c8c;">
                                        <a href="{{ config('app.url') }}/app/nuevo"
                                           style="display:inline-block;padding:12px 22px;font-size:15px;font-weight:bold;color:#ffffff;text-decoration:none;">
                                            Empezar un envío
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            <p style="margin:0 0 6px;font-size:13px;line-height:1.6;color:#64748b;">
                                Datos de tu cuenta:
                            </p>
                            <ul style="margin:0 0 14px;padding-left:18px;font-size:13px;line-height:1.7;color:#475569;">
                                <li>Correo: {{ $usuario->email }}</li>
                                <li>Documento: {{ $usuario->numero_documento }}</li>
                            </ul>
                            <p style="margin:0;font-size:13px;line-height:1.6;color:#94a3b8;">
                                Si no creaste esta cuenta, ignora este mensaje.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background:#f8fafc;padding:16px 28px;border-top:1px solid #e2e8f0;">
                            <p style="margin:0;font-size:12px;color:#94a3b8;">
                                © {{ date('Y') }} Servicios Postales del Perú S.A. — SERPOST Envío Fácil.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
