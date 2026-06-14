@php
    $nat = $form['naturaleza_valor'];
    $kg = fn ($g) => rtrim(rtrim(number_format(((float) $g) / 1000, 3, '.', ''), '0'), '.') . ' kg';
    $ck = fn ($cond) => '<span style="font-family: DejaVu Sans; font-size:10px;">' . ($cond ? '☒' : '☐') . '</span>';
    $r = $form['remitente'];
    $d = $form['destinatario'];
    $tieneCert = ! empty($form['certificado']);
    $factura = $form['documento_comercial'] ? ($form['documento_comercial']['serie'] . '-' . $form['documento_comercial']['numero']) : '';
    $azul = '#1b3c8c';
    $vert = fn ($txt) => implode('<br>', str_split($txt));
@endphp
<div style="font-family: Helvetica, Arial, sans-serif; color:#11203f; border: 1px solid {{ $azul }};">

    <table>
        <tr>
            <td style="width:62%; border-right:1px solid {{ $azul }}; border-bottom:1px solid {{ $azul }}; padding:5px 8px; vertical-align:middle;">
                <span style="color:{{ $azul }}; font-weight:bold; font-size:18px; font-style:italic;">Serpost</span>
                <span style="color:{{ $azul }}; font-size:7px;">El Correo del Perú</span>
                &nbsp;&nbsp;<span style="color:#e2660f; font-weight:bold; font-size:16px;">EMS</span>
                <span style="font-size:6px; color:{{ $azul }};">COURIER INTERNACIONAL DEL PERÚ</span>
                <div style="font-weight:bold; font-size:9px; margin-top:2px;">DECLARACIÓN DE ADUANAS / CUSTOM DECLARATION</div>
            </td>
            <td style="width:38%; border-bottom:1px solid {{ $azul }}; padding:5px 8px; text-align:right; vertical-align:middle;">
                <span style="font-weight:bold; font-size:15px; color:{{ $azul }};">CN 23 EMS</span>
                <div style="font-size:6.5px;">Importante: Ver instrucciones al reverso</div>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td style="width:2.5%; background:{{ $azul }}; color:#fff; font-size:6px; font-weight:bold; text-align:center; border-bottom:1px solid {{ $azul }}; line-height:1.1;">{!! $vert('DE-FROM') !!}</td>
            <td style="width:47.5%; border-right:1px solid {{ $azul }}; border-bottom:1px solid {{ $azul }}; padding:0;">
                <table>
                    <tr><td style="border-bottom:0.5px solid #9aa7c7; padding:2px 5px;"><span style="font-size:6px; color:{{ $azul }};">Remitente / Sender</span><br><span style="font-size:9px; font-weight:bold;">{{ $r->nombre_completo }}</span></td></tr>
                    <tr><td style="border-bottom:0.5px solid #9aa7c7; padding:2px 5px;"><span style="font-size:6px; color:{{ $azul }};">Dirección / Address</span><br><span style="font-size:9px;">{{ $r->direccion }}</span></td></tr>
                    <tr><td style="border-bottom:0.5px solid #9aa7c7; padding:0;">
                        <table><tr>
                            <td style="width:50%; border-right:0.5px solid #9aa7c7; padding:2px 5px;"><span style="font-size:6px; color:{{ $azul }};">Código Postal / Postcode</span><br><span style="font-size:9px;">{{ $r->codigo_postal }}</span></td>
                            <td style="width:50%; padding:2px 5px;"><span style="font-size:6px; color:{{ $azul }};">Ciudad / City</span><br><span style="font-size:9px;">{{ $r->ciudad }}</span></td>
                        </tr></table>
                    </td></tr>
                    <tr><td style="border-bottom:0.5px solid #9aa7c7; padding:0;">
                        <table><tr>
                            <td style="width:50%; border-right:0.5px solid #9aa7c7; padding:2px 5px;"><span style="font-size:6px; color:{{ $azul }};">País / Country</span><br><span style="font-size:9px; font-weight:bold;">PERÚ</span></td>
                            <td style="width:50%; padding:2px 5px;"><span style="font-size:6px; color:{{ $azul }};">Teléfono / Phone</span><br><span style="font-size:9px;">{{ $r->telefono }}</span></td>
                        </tr></table>
                    </td></tr>
                    <tr><td style="padding:2px 5px;"><span style="font-size:6px; color:{{ $azul }};">Correo Electrónico / E-mail</span><br><span style="font-size:8px;">{{ $r->email }}</span></td></tr>
                </table>
            </td>
            <td style="width:2.5%; background:{{ $azul }}; color:#fff; font-size:6px; font-weight:bold; text-align:center; border-bottom:1px solid {{ $azul }}; line-height:1.1;">{!! $vert('PARA-TO') !!}</td>
            <td style="width:45.5%; border-bottom:1px solid {{ $azul }}; padding:0;">
                <table>
                    <tr><td style="border-bottom:0.5px solid #9aa7c7; padding:2px 5px;"><span style="font-size:6px; color:{{ $azul }};">Destinatario / Addressee</span><br><span style="font-size:9px; font-weight:bold;">{{ $d->nombre_completo }}</span></td></tr>
                    <tr><td style="border-bottom:0.5px solid #9aa7c7; padding:2px 5px;"><span style="font-size:6px; color:{{ $azul }};">Dirección / Address</span><br><span style="font-size:9px;">{{ $d->direccion }}</span></td></tr>
                    <tr><td style="border-bottom:0.5px solid #9aa7c7; padding:0;">
                        <table><tr>
                            <td style="width:50%; border-right:0.5px solid #9aa7c7; padding:2px 5px;"><span style="font-size:6px; color:{{ $azul }};">Código Postal / Postcode</span><br><span style="font-size:9px;">{{ $d->codigo_postal }}</span></td>
                            <td style="width:50%; padding:2px 5px;"><span style="font-size:6px; color:{{ $azul }};">Ciudad / City</span><br><span style="font-size:9px;">{{ $d->ciudad }}</span></td>
                        </tr></table>
                    </td></tr>
                    <tr><td style="border-bottom:0.5px solid #9aa7c7; padding:0;">
                        <table><tr>
                            <td style="width:50%; border-right:0.5px solid #9aa7c7; padding:2px 5px;"><span style="font-size:6px; color:{{ $azul }};">País / Country</span><br><span style="font-size:9px; font-weight:bold;">{{ $d->pais }}</span></td>
                            <td style="width:50%; padding:2px 5px;"><span style="font-size:6px; color:{{ $azul }};">Teléfono / Phone</span><br><span style="font-size:9px;">{{ $d->telefono }}</span></td>
                        </tr></table>
                    </td></tr>
                    <tr><td style="padding:2px 5px;"><span style="font-size:6px; color:{{ $azul }};">Correo Electrónico / E-mail</span><br><span style="font-size:8px;">{{ $d->email }}</span></td></tr>
                </table>
            </td>
        </tr>
    </table>

    <table><tr><td style="background:#1f7a3d; color:#fff; font-weight:bold; font-size:8px; text-align:center; padding:2px;">INFORMACIÓN PARA ADUANAS / CUSTOM INFORMATION</td></tr></table>

    <table>
        <tr style="background:#eef2fb;">
            <td style="width:34%; border:0.5px solid {{ $azul }}; padding:3px 5px; font-size:6.5px; font-weight:bold;">(1) Descripción del Contenido / Description of contents</td>
            <td style="width:10%; border:0.5px solid {{ $azul }}; padding:3px; font-size:6.5px; font-weight:bold; text-align:center;">(2) Cantidad / Quantity</td>
            <td style="width:13%; border:0.5px solid {{ $azul }}; padding:3px; font-size:6.5px; font-weight:bold; text-align:center;">(3) Peso Neto (en Kg) / Net Weight Kg</td>
            <td style="width:13%; border:0.5px solid {{ $azul }}; padding:3px; font-size:6.5px; font-weight:bold; text-align:center;">(04) Valor / Value</td>
            <td style="width:14%; border:0.5px solid {{ $azul }}; padding:3px; font-size:6px; font-weight:bold; text-align:center;">(05) País de origen / Country of origin</td>
            <td style="width:16%; border:0.5px solid {{ $azul }}; padding:3px; font-size:6px; font-weight:bold; text-align:center;">Sub partida Arancel SA / HS tariff number</td>
        </tr>
        @foreach ($form['articulos'] as $a)
            <tr>
                <td style="border:0.5px solid {{ $azul }}; padding:3px 5px; font-size:8px;">{{ $a['descripcion'] }}</td>
                <td style="border:0.5px solid {{ $azul }}; padding:3px; font-size:8px; text-align:center;">{{ $a['cantidad'] }}</td>
                <td style="border:0.5px solid {{ $azul }}; padding:3px; font-size:8px; text-align:center;">{{ $kg($a['peso_neto_gramos']) }}</td>
                <td style="border:0.5px solid {{ $azul }}; padding:3px; font-size:8px; text-align:center;">{{ number_format($a['valor'], 0) }} {{ $form['divisa'] }}</td>
                <td style="border:0.5px solid {{ $azul }}; padding:3px; font-size:8px; text-align:center;">{{ $a['pais_origen'] }}</td>
                <td style="border:0.5px solid {{ $azul }}; padding:3px; font-size:8px; text-align:center;">{{ $a['codigo_hs'] ?: '' }}</td>
            </tr>
        @endforeach
        @for ($i = count($form['articulos']); $i < 3; $i++)
            <tr><td style="border:0.5px solid {{ $azul }}; height:14px;">&nbsp;</td><td style="border:0.5px solid {{ $azul }};"></td><td style="border:0.5px solid {{ $azul }};"></td><td style="border:0.5px solid {{ $azul }};"></td><td style="border:0.5px solid {{ $azul }};"></td><td style="border:0.5px solid {{ $azul }};"></td></tr>
        @endfor
    </table>

    <table>
        <tr>
            <td style="width:44%; border:0.5px solid {{ $azul }}; padding:3px 5px; font-size:7px;"><b>Peso Bruto Total / Total Gross Weight:</b> {{ $form['peso_bruto'] }} kg</td>
            <td style="width:26%; border:0.5px solid {{ $azul }}; padding:3px 5px; font-size:7px;"><b>Valor Total / Total Value:</b> {{ number_format($form['totales']['valor'], 0) }} {{ $form['divisa'] }}</td>
            <td style="width:30%; background:{{ $azul }}; color:#fff; border:0.5px solid {{ $azul }}; padding:3px 5px; font-size:7px; font-weight:bold; text-align:center;">Información Entrega Destino / Destination Delivery Information</td>
        </tr>
    </table>

    <table>
        <tr>
            <td style="width:44%; border:0.5px solid {{ $azul }}; padding:4px 6px; font-size:7px; vertical-align:top;">
                <div style="color:#e2660f; font-weight:bold; margin-bottom:3px;">(06) Categoría del producto / Category of item</div>
                <table style="font-size:7.5px;">
                    <tr>
                        <td style="padding:1px 4px;">{!! $ck(in_array($nat, ['venta','variado','otro'])) !!} Mercadería / Merchandise</td>
                        <td style="padding:1px 4px;">{!! $ck($nat === 'muestra') !!} Muestras / Samples</td>
                    </tr>
                    <tr>
                        <td style="padding:1px 4px;">{!! $ck($nat === 'documentos') !!} Documentos / Documents</td>
                        <td style="padding:1px 4px;">{!! $ck($nat === 'regalo') !!} Obsequios / Gifts</td>
                    </tr>
                </table>
            </td>
            <td style="width:26%; border:0.5px solid {{ $azul }}; padding:4px 6px; font-size:6.5px; vertical-align:top;">
                <div style="font-weight:bold; margin-bottom:3px;">(07) Mercadería sujeta a cuarentena, controles sanitarios u otras restricciones</div>
                {!! $ck($tieneCert) !!} SI &nbsp;&nbsp; {!! $ck(! $tieneCert) !!} NO
                <div style="margin-top:4px;">Firma, código y sello del expedidor</div>
            </td>
            <td style="width:30%; border:0.5px solid {{ $azul }}; padding:4px 6px; font-size:6.5px; vertical-align:top;">
                <table style="font-size:6.5px;">
                    <tr><td style="width:50%; padding:1px;">Fecha / Date:</td><td style="padding:1px;">Hora / Time:</td></tr>
                    <tr><td colspan="2" style="padding:3px 1px;">Nombre destinatario / Name Person</td></tr>
                    <tr><td colspan="2" style="padding:3px 1px;">Firma / Signature</td></tr>
                    <tr><td style="padding:3px 1px;">Total Peso admitido / Kg</td><td style="padding:3px 1px;">Huella Digital Depositante</td></tr>
                    <tr><td style="padding:1px;">Valor Porte / freight rate S/.</td><td style="padding:1px;"></td></tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="border:0.5px solid {{ $azul }}; padding:3px 6px; font-size:7px;">
                <b>N° Licencia:</b> {{ $form['certificado']['licencia'] ?? '' }} &nbsp;&nbsp;
                <b>N° Certificado:</b> {{ $form['certificado']['certificado'] ?? '' }} &nbsp;&nbsp;
                <b>N° Factura:</b> {{ $factura }}
            </td>
            <td style="border:0.5px solid {{ $azul }};"></td>
        </tr>
    </table>

    <table>
        <tr><td style="border:0.5px solid {{ $azul }}; padding:3px 6px; font-size:7px;">
            <b>Operador de origen / Operator of Origin</b> &nbsp;—&nbsp;
            Fecha: {{ now()->format('d/m/Y') }} &nbsp; Hora: {{ now()->format('H:i') }} &nbsp;
            Oficina: {{ $form['oficina'] }} &nbsp; Representante Comercial: ____ &nbsp; · Pre-admisión: <b>{{ $form['codigo'] }}</b>
        </td></tr>
    </table>

    <table>
        <tr><td style="padding:3px 6px; font-size:6.5px; font-style:italic;">
            Certifico que la información contenida en la presente Declaración de Aduanas es correcta y que las mercancías declaradas no
            contienen ningún artículo peligroso y/o prohibido por las normas de la Unión Postal Universal (UPU) ni por la legislación Nacional.
        </td></tr>
    </table>

    <table>
        <tr>
            <td style="width:40%; border:0.5px solid {{ $azul }}; padding:4px 6px; font-size:7px;"><b>(08) Datos de la persona que deposita el envío</b><br>Nombres y Apellidos:<div style="height:16px;"></div></td>
            <td style="width:35%; border:0.5px solid {{ $azul }}; padding:4px 6px; font-size:7px;">Documento de Identidad:<div style="height:16px;"></div></td>
            <td style="width:25%; border:0.5px solid {{ $azul }}; padding:4px 6px; font-size:7px;">Firma:<div style="height:16px;"></div></td>
        </tr>
    </table>
</div>
