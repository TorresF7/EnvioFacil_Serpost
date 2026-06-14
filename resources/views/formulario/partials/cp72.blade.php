@php
    $nat = $form['naturaleza_valor'];
    $ck = fn ($cond) => '<span style="font-family: DejaVu Sans; font-size:10px;">' . ($cond ? '☒' : '☐') . '</span>';
    $r = $form['remitente'];
    $d = $form['destinatario'];
    $ins = $form['instruccion_no_entrega'];
    $tieneCert = ! empty($form['certificado']);
    $azul = '#1b3c8c';
    $vert = fn ($txt) => implode('<br>', str_split($txt));
    $lab = 'font-size:6px; font-weight:bold; color:' . $azul . '; background:#eef2fb;';
    $val = 'font-size:9px;';
    $bd = '0.5px solid ' . $azul;
    $badge = fn ($n) => '<td style="width:16px; background:' . $azul . '; color:#fff; font-weight:bold; text-align:center; font-size:10px; border:' . $bd . ';">' . $n . '</td>';
@endphp
<div style="font-family: Helvetica, Arial, sans-serif; color:#11203f; border: 1px solid {{ $azul }};">

    <table>
        <tr>
            <td style="width:60%; border-right:1px solid {{ $azul }}; border-bottom:1px solid {{ $azul }}; padding:8px 12px; vertical-align:middle;">
                <span style="color:{{ $azul }}; font-weight:bold; font-size:22px; font-style:italic;">Serpost</span><br>
                <span style="color:{{ $azul }}; font-size:9px; font-style:italic;">El Correo del Perú</span>
            </td>
            <td style="width:40%; border-bottom:1px solid {{ $azul }}; padding:8px 12px; vertical-align:middle;">
                <span style="color:{{ $azul }}; font-weight:bold; font-size:20px; font-style:italic;">CP-72</span>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td style="vertical-align:top; padding:0; border-right:1px solid {{ $azul }};">

                <table>
                    <tr>
                        <td rowspan="4" style="width:2.5%; background:{{ $azul }}; color:#fff; font-size:6px; font-weight:bold; text-align:center; line-height:1.1; border-bottom:{{ $bd }};">{!! $vert('DE-FROM') !!}</td>
                        {!! $badge(1) !!}
                        <td style="width:15%; {{ $lab }} border:{{ $bd }};">Remitente<br>Sender</td>
                        <td style="width:43%; {{ $val }} border:{{ $bd }}; padding:2px 5px;">{{ $r->nombre_completo }}</td>
                        <td style="width:22%; {{ $lab }} border:{{ $bd }};">Documento de Identidad<br>del Remitente/Sender's ID</td>
                        <td style="{{ $val }} border:{{ $bd }}; padding:2px 5px;">{{ $r->ruc ?: $r->numero_documento }}</td>
                    </tr>
                    <tr>
                        <td style="{{ $lab }} border:{{ $bd }};">Dirección<br>Address</td>
                        <td colspan="3" style="{{ $val }} border:{{ $bd }}; padding:2px 5px;">{{ $r->direccion }}</td>
                    </tr>
                    <tr>
                        <td style="{{ $lab }} border:{{ $bd }};">Código Postal<br>Postal Code</td>
                        <td style="{{ $val }} border:{{ $bd }}; padding:2px 5px;">{{ $r->codigo_postal }}
                            <span style="{{ $lab }} margin-left:6px;">Ciudad / City</span> <span style="{{ $val }}">{{ $r->ciudad }}</span>
                        </td>
                        <td style="{{ $lab }} border:{{ $bd }};">País<br>Country</td>
                        <td style="{{ $val }} border:{{ $bd }}; padding:2px 5px;">PERÚ</td>
                    </tr>
                    <tr>
                        <td style="{{ $lab }} border:{{ $bd }};">Persona de Contacto<br>Contact Person</td>
                        <td style="{{ $val }} border:{{ $bd }}; padding:2px 5px;">
                            <span style="{{ $lab }}">Tel/Fax</span> {{ $r->telefono }}
                            <span style="{{ $lab }} margin-left:6px;">E-mail</span> {{ $r->email }}
                        </td>
                        <td colspan="2" style="border:{{ $bd }};"></td>
                    </tr>
                </table>

                <table>
                    <tr>
                        <td rowspan="4" style="width:2.5%; background:{{ $azul }}; color:#fff; font-size:6px; font-weight:bold; text-align:center; line-height:1.1; border-bottom:{{ $bd }};">{!! $vert('PARA-TO') !!}</td>
                        {!! $badge(2) !!}
                        <td style="width:15%; {{ $lab }} border:{{ $bd }};">Destinatario<br>Addressee</td>
                        <td colspan="3" style="{{ $val }} border:{{ $bd }}; padding:2px 5px;">{{ $d->nombre_completo }}</td>
                    </tr>
                    <tr>
                        <td style="{{ $lab }} border:{{ $bd }};">Dirección<br>Address</td>
                        <td colspan="3" style="{{ $val }} border:{{ $bd }}; padding:2px 5px;">{{ $d->direccion }}</td>
                    </tr>
                    <tr>
                        <td style="{{ $lab }} border:{{ $bd }};">Código Postal<br>Postal Code</td>
                        <td style="width:43%; {{ $val }} border:{{ $bd }}; padding:2px 5px;">{{ $d->codigo_postal }}
                            <span style="{{ $lab }} margin-left:6px;">Ciudad / City</span> <span style="{{ $val }}">{{ $d->ciudad }}</span>
                        </td>
                        <td style="width:22%; {{ $lab }} border:{{ $bd }};">País<br>Country</td>
                        <td style="{{ $val }} border:{{ $bd }}; padding:2px 5px;">{{ $d->pais }}</td>
                    </tr>
                    <tr>
                        <td style="{{ $lab }} border:{{ $bd }};">Persona de Contacto<br>Contact Person</td>
                        <td colspan="3" style="{{ $val }} border:{{ $bd }}; padding:2px 5px;">
                            <span style="{{ $lab }}">Tel/Fax</span> {{ $d->telefono }}
                            <span style="{{ $lab }} margin-left:6px;">E-mail</span> {{ $d->email }}
                        </td>
                    </tr>
                </table>

                <table>
                    <tr style="background:#eef2fb;">
                        {!! $badge(3) !!}
                        <td style="width:13%; {{ $lab }} border:{{ $bd }};">Sub Partida<br>Nacional</td>
                        <td style="width:33%; {{ $lab }} border:{{ $bd }};">Declaración de Aduana / Customs Declaration<br>descripción del Contenido / Contents Description</td>
                        <td style="width:8%; {{ $lab }} border:{{ $bd }};">Cantidad</td>
                        <td style="width:14%; {{ $lab }} border:{{ $bd }};">Comprobante de Pago</td>
                        <td style="width:9%; {{ $lab }} border:{{ $bd }};">Código País de Origen</td>
                        <td style="width:12%; {{ $lab }} border:{{ $bd }};">Régimen de Procedencia</td>
                        <td style="{{ $lab }} border:{{ $bd }};">Valor FOB $</td>
                    </tr>
                    @foreach ($form['articulos'] as $a)
                        <tr>
                            <td style="border:{{ $bd }};"></td>
                            <td style="{{ $val }} border:{{ $bd }}; padding:2px 4px; text-align:center;">{{ $a['codigo_hs'] ?: '' }}</td>
                            <td style="{{ $val }} border:{{ $bd }}; padding:2px 5px;">{{ $a['descripcion'] }}</td>
                            <td style="{{ $val }} border:{{ $bd }}; padding:2px 4px; text-align:center;">{{ $a['cantidad'] }}</td>
                            <td style="{{ $val }} border:{{ $bd }};"></td>
                            <td style="{{ $val }} border:{{ $bd }}; padding:2px 4px; text-align:center;">{{ $a['pais_origen'] }}</td>
                            <td style="{{ $val }} border:{{ $bd }};"></td>
                            <td style="{{ $val }} border:{{ $bd }}; padding:2px 4px; text-align:center;">{{ number_format($a['valor'], 0) }} {{ $form['divisa'] }}</td>
                        </tr>
                    @endforeach
                    @for ($i = count($form['articulos']); $i < 3; $i++)
                        <tr><td style="border:{{ $bd }}; height:13px;"></td><td style="border:{{ $bd }};"></td><td style="border:{{ $bd }};"></td><td style="border:{{ $bd }};"></td><td style="border:{{ $bd }};"></td><td style="border:{{ $bd }};"></td><td style="border:{{ $bd }};"></td><td style="border:{{ $bd }};"></td></tr>
                    @endfor
                </table>

                <table>
                    <tr>
                        {!! $badge(4) !!}
                        <td style="width:40%; border:{{ $bd }}; padding:3px 5px; font-size:7px;">
                            <div style="font-weight:bold; margin-bottom:2px;">Instrucciones del Remitente en caso de no efectuarse la entrega</div>
                            {!! $ck($ins === 'devolver') !!} Devolver / Return &nbsp;&nbsp; {!! $ck($ins === 'abandonar') !!} Abandonar / Abandon
                        </td>
                        <td style="width:35%; border:{{ $bd }}; padding:3px 5px; font-size:6.5px;">
                            <div style="font-weight:bold; margin-bottom:2px;">Mercadería sujeta a cuarentena, a controles sanitarios, fitosanitarios u otras restricciones</div>
                            {!! $ck($tieneCert) !!} SI &nbsp;&nbsp; {!! $ck(! $tieneCert) !!} NO
                        </td>
                        <td style="border:{{ $bd }}; padding:3px 5px; font-size:7px; font-weight:bold; color:{{ $azul }}; text-align:center;">HUELLA DIGITAL<br>DEL DEPOSITANTE</td>
                    </tr>
                </table>

                <table>
                    <tr>
                        {!! $badge(5) !!}
                        <td style="width:14%; {{ $lab }} border:{{ $bd }}; text-align:center; vertical-align:middle;">Oficina de<br>Origen</td>
                        <td style="width:46%; border:{{ $bd }}; padding:3px 5px; font-size:7px;">
                            <div><b>Fecha y Hora de Depósito de Origen:</b> {{ now()->format('d/m/Y H:i') }} &nbsp; · Oficina: {{ $form['oficina'] }}</div>
                            <div style="margin-top:6px;">Nombre / Firma / Código Expendedor &nbsp; · Pre-admisión: <b>{{ $form['codigo'] }}</b></div>
                        </td>
                        {!! $badge(6) !!}
                        <td style="{{ $lab }} border:{{ $bd }};">Peso Bruto<br>( Tarifado )</td>
                        <td style="{{ $val }} border:{{ $bd }}; padding:2px 5px;">{{ $form['peso_bruto'] }} kg</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="border:{{ $bd }};"></td>
                        {!! $badge(7) !!}
                        <td style="{{ $lab }} border:{{ $bd }};">Valor Total<br>del Flete</td>
                        <td style="{{ $val }} border:{{ $bd }}; padding:2px 5px;">{{ $form['gastos_porte'] ? number_format($form['gastos_porte'], 2) : '' }}</td>
                    </tr>
                </table>

                <table>
                    <tr>
                        {!! $badge(8) !!}
                        <td style="border:{{ $bd }}; padding:4px 6px; font-size:6.5px;">
                            Certifico que la información contenida en la presente Declaración es exacta, que las mercancías declaradas no constituyen objeto
                            peligroso y/o prohibido por las normas de la Unión Postal Universal (UPU) ni por la legislación interna. Acepto también pagar los gastos
                            resultantes de la ejecución de las instrucciones impartidas aquí para el caso en que no pueda ser entregado.
                            <div style="margin-top:8px;">Nombre / Firma / Documento de Identidad de la persona que Deposita el envío.</div>
                        </td>
                    </tr>
                </table>

            </td>
            <td style="width:3%; background:{{ $azul }}; color:#fff; font-size:8px; font-weight:bold; text-align:center; vertical-align:middle; line-height:1.4;">{!! $vert('DESTINO') !!}</td>
        </tr>
    </table>
</div>
