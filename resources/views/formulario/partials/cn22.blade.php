@php
    $nat = $form['naturaleza_valor'];
    $kg = fn ($g) => rtrim(rtrim(number_format(((float) $g) / 1000, 3, '.', ''), '0'), '.') . ' kg';
    $ck = fn ($cond) => '<span style="font-family: DejaVu Sans; font-size:11px;">' . ($cond ? '☒' : '☐') . '</span>';
    $art = $form['articulos'][0] ?? null;
    $descr = collect($form['articulos'])->map(fn ($a) => $a['descripcion'] . ' (x' . $a['cantidad'] . ')')->implode(', ');
    $hs = $form['articulos'][0]['codigo_hs'] ?? null;
@endphp
<div style="font-family: Helvetica, Arial, sans-serif; color:#1a1a2e; width: 175mm; margin: 4mm auto 0; border: 1.2px solid #1a1a2e;">

    <table>
        <tr>
            <td style="width:50%; border-right:1.2px solid #1a1a2e; border-bottom:1.2px solid #1a1a2e; padding:10px 12px;">
                <div style="font-size:15px; font-weight:bold; line-height:1.15;">DECLARACIÓN<br>DE ADUANA</div>
                <div style="font-size:8px;">DECLARATION EN DOUANE</div>
            </td>
            <td style="width:34%; border-right:1.2px solid #1a1a2e; border-bottom:1.2px solid #1a1a2e; padding:10px 12px;">
                <div style="font-size:11.5px; font-weight:bold; line-height:1.2;">Puede ser abierto<br>de oficio</div>
                <div style="font-size:8px;">Peut étre ouvert d' office</div>
            </td>
            <td style="width:16%; border-bottom:1.2px solid #1a1a2e; padding:10px 12px; text-align:right; vertical-align:top;">
                <span style="font-size:19px; font-weight:bold;">CN22</span>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td style="width:62%; border-right:1.2px solid #1a1a2e; border-bottom:1.2px solid #1a1a2e; padding:8px 12px;">
                <div style="font-size:12px; font-weight:bold;">Administración de Correos</div>
                <div style="font-size:8px;">Administration des postes</div>
            </td>
            <td style="width:38%; border-bottom:1.2px solid #1a1a2e; padding:8px 12px;">
                <div style="font-size:11px; font-weight:bold;">¡Importante!</div>
                <div style="font-size:10px; font-weight:bold;">Ver instrucciones al dorso</div>
                <div style="font-size:8.5px; font-weight:bold;">¡Important!</div>
                <div style="font-size:8.5px;">Voir instructions su verso</div>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td style="width:31%; border-right:1.2px solid #1a1a2e; border-bottom:1.2px solid #1a1a2e; padding:6px 10px; font-size:9px;">
                <div>{!! $ck($nat === 'regalo') !!} Regalo<br><span style="margin-left:13px;">Cadeau</span></div>
                <div style="margin-top:4px;">{!! $ck($nat === 'documentos') !!} Documento<br><span style="margin-left:13px;">Documents</span></div>
            </td>
            <td style="width:34%; border-right:1.2px solid #1a1a2e; border-bottom:1.2px solid #1a1a2e; padding:6px 10px; font-size:9px;">
                <div>{!! $ck($nat === 'muestra') !!} Muestra Comercial<br><span style="margin-left:13px;">Echantillon commercial</span></div>
                <div style="margin-top:4px;">{!! $ck(in_array($nat, ['venta','variado','otro','devolucion'])) !!} Otro<br><span style="margin-left:13px;">Autre</span></div>
            </td>
            <td style="width:35%; border-bottom:1.2px solid #1a1a2e; padding:6px 10px; font-size:9px; vertical-align:bottom;">
                Marcar la o las casillas<br>cocher la ou les cases apropnées
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td style="width:65%; border-right:1.2px solid #1a1a2e; border-bottom:1.2px solid #1a1a2e; padding:7px 12px;">
                <div style="font-size:11px; font-weight:bold;">Cantidad y descripción detallada del contenido (1)</div>
                <div style="font-size:8px;">Cuantité et descrition détaillée du contenu (1)</div>
            </td>
            <td style="width:18%; border-right:1.2px solid #1a1a2e; border-bottom:1.2px solid #1a1a2e; padding:7px 10px;">
                <div style="font-size:11px; font-weight:bold;">Peso</div>
                <div style="font-size:9px;">(en kg) (2)</div>
                <div style="font-size:7.5px;">Poids (en kg.) (2)</div>
            </td>
            <td style="width:17%; border-bottom:1.2px solid #1a1a2e; padding:7px 10px;">
                <div style="font-size:11px; font-weight:bold;">Valor (3)</div>
                <div style="font-size:8px;">Valeur (3)</div>
            </td>
        </tr>

        <tr>
            <td style="border-right:1.2px solid #1a1a2e; border-bottom:1.2px solid #1a1a2e; padding:10px 12px; font-size:10px; height:78px; vertical-align:top;">{{ $descr }}</td>
            <td style="border-right:1.2px solid #1a1a2e; border-bottom:1.2px solid #1a1a2e; padding:10px; font-size:10px; vertical-align:top;">{{ $kg($form['totales']['peso_gramos']) }}</td>
            <td style="border-bottom:1.2px solid #1a1a2e; padding:10px; font-size:10px; vertical-align:top;">{{ number_format($form['totales']['valor'], 0) }} {{ $form['divisa'] }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <td style="width:65%; border-right:1.2px solid #1a1a2e; border-bottom:1.2px solid #1a1a2e; padding:7px 12px;">
                <div style="font-size:11px; font-weight:bold;">Solo para los envíos comerciales</div>
                <div style="font-size:11px; font-weight:bold;">N° tarifario del SA (4) y país de origen</div>
                <div style="font-size:11px; font-weight:bold;">de las merdaderías (si se conoce) (5)</div>
                <div style="font-size:7.5px;">N° tarifaire du SH(4) et pays d'origine desmarchandises (si connus)(5)</div>
            </td>
            <td style="width:18%; border-right:1.2px solid #1a1a2e; border-bottom:1.2px solid #1a1a2e; padding:7px 10px;">
                <div style="font-size:11px; font-weight:bold;">Peso total</div>
                <div style="font-size:9px;">(en kg) (6)</div>
                <div style="font-size:7.5px;">Poids total (en kg.) (6)</div>
            </td>
            <td style="width:17%; border-bottom:1.2px solid #1a1a2e; padding:7px 10px;">
                <div style="font-size:11px; font-weight:bold;">Valor</div>
                <div style="font-size:9px;">Total (7)</div>
                <div style="font-size:7.5px;">Valeur totale (7)</div>
            </td>
        </tr>
        <tr>
            <td style="border-right:1.2px solid #1a1a2e; border-bottom:1.2px solid #1a1a2e; padding:14px 12px; font-size:10px; height:70px; vertical-align:top;">{{ $hs ? $hs . ' · ' . ($art['pais_origen'] ?? 'Perú') : '' }}</td>
            <td style="border-right:1.2px solid #1a1a2e; border-bottom:1.2px solid #1a1a2e; padding:14px 10px; font-size:10px; vertical-align:top;">{{ $kg($form['totales']['peso_gramos']) }}</td>
            <td style="border-bottom:1.2px solid #1a1a2e; padding:14px 10px; font-size:10px; vertical-align:top;">{{ number_format($form['totales']['valor'], 0) }} {{ $form['divisa'] }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <td style="padding:12px 14px;">
                <div style="font-size:11px; font-weight:bold; line-height:2.0;">
                    El insfrascrito, cuyo nombre y dirección figuran en el envío, certifica<br>
                    que la información dad en la presente declaración es exacta y<br>
                    que este envío no contiene ningún objeto peligroso o prohibido por<br>
                    la legislación o por la reglamentación postal o aduanera<br>
                    Fecha y firma del expedidor(8)
                </div>
                <div style="font-size:7.5px; margin-top:8px; line-height:1.35;">
                    Je. sossigné dont le nom et l'adresse figurent sur l'envoi certifie que les reseignements donnés dans la présente
                    déclarationsont exacts et que cet envoi ne contient aucun objet dangereux ou interdit par la législation ou la
                    réglamentation postale ou<br>douaniére<br>
                    Date et signature de l'expéditeur (8)
                </div>
            </td>
        </tr>
    </table>
</div>
