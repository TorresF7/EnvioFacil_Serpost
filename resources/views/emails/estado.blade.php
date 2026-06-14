<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualización de tu envío SERPOST</title>
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
                            <h1 style="margin:0 0 12px;font-size:20px;color:#0f172a;">
                                ¡Hola{{ $envio->user ? ', ' . $envio->user->name : '' }}!
                            </h1>
                            <p style="margin:0 0 14px;font-size:15px;line-height:1.6;color:#334155;">
                                Tu envío <strong>{{ $envio->codigo }}</strong> tiene una nueva actualización de estado:
                            </p>
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:18px 0;background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;">
                                <tr>
                                    <td style="padding:18px 20px;">
                                        <p style="margin:0 0 4px;font-size:12px;text-transform:uppercase;letter-spacing:0.5px;color:#94a3b8;">
                                            Estado actual
                                        </p>
                                        <p style="margin:0 0 8px;font-size:18px;font-weight:bold;color:#1b3c8c;">
                                            {{ $envio->estado->label() }}
                                        </p>
                                        <p style="margin:0;font-size:14px;line-height:1.6;color:#475569;">
                                            {{ $envio->estado->descripcion() }}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                            <table role="presentation" cellpadding="0" cellspacing="0" style="margin:18px 0;">
                                <tr>
                                    <td style="border-radius:8px;background:#1b3c8c;">
                                        <a href="{{ config('app.url') }}/app/envios"
                                           style="display:inline-block;padding:12px 22px;font-size:15px;font-weight:bold;color:#ffffff;text-decoration:none;">
                                            Ver mis envíos
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            <p style="margin:0;font-size:13px;line-height:1.6;color:#94a3b8;">
                                Recibes este aviso porque activaste las notificaciones de este envío.
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
