<!DOCTYPE html>
<html>
<head>
    <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/font-awesome.min.css') }}" rel="stylesheet">
    <script src="{{ asset('/js/jquery.min.js') }}"></script>
    <script src="{{ asset('/js/bootstrap.min.js') }}"></script>
    <style type="text/css" media="all">
        body {background: none white;  content: "©"; padding: 0;  width: {{$config['ticket_with']}}in !important;}
        .row{ margin: 0;  padding: 0; width: 100%; position: relative; font-size: 12px;}
        .clear { clear: both;}
        .title{ text-transform: uppercase; }
        .title_pie{ text-transform: uppercase; font-size: 10px; }
        h5 {
            margin: 0;
            padding:5px 5px 5px 5px;
        }
        .txt-center {
            text-align: center;
        }
        .border {
            border-top: 5px dotted black;
            border-bottom: 5px dotted black;
        }
        .separator{ width: 100%; height: 15px; }
        .ticket_list{ border-top: 1px dashed black; padding: 10px 0 10px 0; }
        .totals div{width: 100%;}
    </style>
    <style type="text/css" media="screen">
        body{width: 100% !important;}
        .ticket{display: none; margin: 0 15px;}
    </style>
    <style type="text/css" media="print">
        .print-button{display: none;}
    </style>
</head>
<body>
<input type="hidden" name="_token" value="{{csrf_token()}}" id="token">
<div class="ticket">
    <!-- EMPRESA -->
    <div class="txt-center">
        <div class="row">
            <h5 class="title">{{ $site['name'] }}</h5>
        </div>
        <div class="row">
            <h5>{{ $site['address'] }}</h5>
        </div>
    <!-- <div class="row">
        <h5>{{ $site['contact'] }}</h5>
    </div> -->
        <div class="row">
            <h5>Tel: {{$site['phone'] }}</h5>
        </div>
        <div class="row">
            <h5> C.I.F: {{ $site['cif'] }}</h5>
        </div>
    </div>

<!-- CLIENTE
    <div class="custom_row title" style="margin-top: 10px;">
        <h2><b><em>Datos del cliente</em></b></h2>
    </div>
    <div class="custom_row title">
        <h5>{{  $site['client']['name'] }}</h5>
    </div>
    <div class="custom_row">
        <h2>{{$site['client']['address'] }}</h2>
    </div>
    <div class="row">
        <h2>{{ $site['client']['dni']  }}</h2>
    </div>
    <div class="row">
        <h2>{{ $site['client']['phone']  }}</h2>
    </div> -->

    <div class="separator"></div>
    <div class="row txt-center">
        <h5>Factura Simplificada</h5>
    </div>
    <div class="row txt-center" style="border-top: 1px dashed black">

        <table width="100%">
            <tr style="text-align: left; color: black; font-weight: bold;">
                <td>Cajero</td>
                <td>Fecha</td>
                <td>Hora</td>
                <td>Venta</td>
            </tr>
            <tr style="text-align: left">
                <td>{{  $site['atendido']}}</td>
                <td>{{ \Carbon\Carbon::parse($ticket['created_at'])->format('d-m-Y')}}</td>
                <td>{{ \Carbon\Carbon::parse($ticket['created_at'])->format('H:i')}}</td>
                <td> {{ $ticket['code'] }}</td>
            </tr>
        </table>

    </div>
    <div class="separator"></div>
    <div class="clear"></div>
    <div class="ticket_list">
        <div class="row">
            <div style="width: 40%; float: left">
                <b>Articulo</b>
            </div>
            <div  style="width: 20%;float: left">
                <b>Cantidad</b>
            </div>
            <div  style="width: 20%;float: left">
                <b>PVP</b>
            </div>

            <div  style="width: 20%;float:right; text-align: right">
                <b>Importe</b>
            </div>
        </div>
        <div class="clear"></div>
        @foreach($articles as $article)
            <div class="row" style="margin-top: 5px">
                <div  style="width: 40%; float: left;font-size: 10px;">
                    {{ $article['name']}}
                </div>
                <div style="width: 20%;float: left;font-size: 10px;">
                    {{ $article['units']}}
                </div>
                <div style="width: 20%;float: left;font-size: 10px;">
                    {{ $article['price'] }}
                </div>
                <div style="width: 20%;float:right; text-align: right;font-size: 10px;">
                    {{ $article['fullimport'] }}
                </div>
            </div>
            <div class="row" style="margin-top: 15px">
                <div style="width: 80%;float: left;font-size: 10px; text-align: right">
                    Subtotal:
                </div>
                <div style="width: 20%;float:right; text-align: right;font-size: 10px;">
                    <b>{{ $article['fullimport'] }}</b>
                </div>
            </div>
            <div class="clear"></div>
        @endforeach
    </div>
    <div class="clear"></div>
    <div class="separator"></div>
    <div class="row" style="border-top: 1px dashed black; border-bottom: 1px dashed black; padding: 10px  0 10px 0">
        <div class="row ">
            <span style="margin-right: 15px">TOTAL IVA INC</span> :  <b>{{ $totals['total_full'] }}</b>
        </div>
        <div class="row ">
            <span style="margin-right: 22px">BASE IMP IVA </span> :  <b>{{ $totals['subtotal'] }}</b>
        </div>
        <div class="row ">
            <span style="margin-right:53px">IVA 21%</span> :  <b>{{ $totals['total_tax'] }}</b>
        </div>
        <div class="row">
            <span style="margin-right: 53px">Recivido</span> : <b> {{ $ticket['received'] }}</b>
        </div>
        <div class="row">
            <span style="margin-right: 57px">Cambio</span> : <b> {{ $ticket['change'] }}</b>
        </div>
    </div>
    <div class="clear"></div>
    <div class="separator"></div>
    <div class="row txt-center title_pie">
        <p>No es tornaran els doblers en cas de dev </p>
        <p>Compres realizades abans 5 de gener </p>
        <p>Devolucions fins dia 15 de gener</p>
        @if ($config['mode'] == 'COPIA')
            <h5>{{$config['mode']}}</h5>
        @endif
    </div>


    <!-- Regalo -->
    @if ($gift == 1)
        <div class="separator"></div>
        <div class="clear"></div>
        <!-- EMPRESA -->
        <div class="txt-center">
            <div class="row">
                <h5 class="title">{{ $site['name'] }}</h5>
            </div>
            <div class="row">
                <h5>{{ $site['address'] }}</h5>
            </div>
        <!-- <div class="row">
        <h5>{{ $site['contact'] }}</h5>
    </div> -->
            <div class="row">
                <h5>Tel: {{$site['phone'] }}</h5>
            </div>
            <div class="row">
                <h5> C.I.F: {{ $site['cif'] }}</h5>
            </div>
        </div>

    <!-- CLIENTE
    <div class="custom_row title" style="margin-top: 10px;">
        <h2><b><em>Datos del cliente</em></b></h2>
    </div>
    <div class="custom_row title">
        <h5>{{  $site['client']['name'] }}</h5>
    </div>
    <div class="custom_row">
        <h2>{{$site['client']['address'] }}</h2>
    </div>
    <div class="row">
        <h2>{{ $site['client']['dni']  }}</h2>
    </div>
    <div class="row">
        <h2>{{ $site['client']['phone']  }}</h2>
    </div> -->

        <div class="separator"></div>
        <div class="row txt-center">
            <h5>Factura Simplificada</h5>
        </div>
        <div class="row txt-center" style="border-top: 1px dashed black">

            <table width="100%">
                <tr style="text-align: left; color: black; font-weight: bold;">
                    <td>Cajero</td>
                    <td>Fecha</td>
                    <td>Hora</td>
                    <td>Venta</td>
                </tr>
                <tr style="text-align: left">
                    <td>{{  $site['atendido']}}</td>
                    <td>{{ \Carbon\Carbon::parse($ticket['created_at'])->format('d-m-Y')}}</td>
                    <td>{{ \Carbon\Carbon::parse($ticket['created_at'])->format('H:i')}}</td>
                    <td> {{ $ticket['code'] }}</td>
                </tr>
            </table>

        </div>
        <div class="separator"></div>
        <div class="clear"></div>
        <div class="ticket_list">
            <div class="row">
                <div style="width: 50%; float: left">
                    <b>Articulo</b>
                </div>
                <div  style="width: 50%;float: left">
                    <b>Cantidad</b>
                </div>
            </div>
            <div class="clear"></div>
            @foreach($articles as $article)
                <div class="row" style="margin-top: 5px">
                    <div  style="width: 50%; float: left;font-size: 10px;">
                        {{ $article['name']}}
                    </div>
                    <div style="width: 50%;float: left;font-size: 10px;">
                        {{ $article['units']}}
                    </div>
                </div>
            @endforeach
        </div>
        <div class="clear"></div>
        <div class="row txt-center title_pie" style="font-size: 14px">
            REGALO
        </div>
        <div class="separator"></div>
        <div class="row txt-center title_pie">
            <p>No es tornaran els doblers en cas de dev </p>
            <p>Compres realizades abans 5 de gener </p>
            <p>Devolucions fins dia 15 de gener</p>
            @if ($config['mode'] == 'COPIA')
                <h5>{{$config['mode']}}</h5>
            @endif
        </div>
    @endif
</div>

<div class="print-button">
    @if ($config['copy'] == false)
    <p>El ticket se ha creado correctamente, puede imprimirlo presionando el bot&oacute;n de imprimir</p>
    @else
    <p>El ticket se ha creado correctamente, puede imprimirlo presionando el bot&oacute;n de imprimir</p>
    @endif
    <button class="btn btn-success btn-sm" onclick="javascript:print()"><i class="fa fa-print"></i>Imprimir</button>
</div>
<script type="text/javascript">
    window.print();
</script>
</body>
</html>