<!DOCTYPE html>
<html>
<head>
      <style type="text/css" media="all">
        body{background: none white; padding: 0px;  width: 4in !important;}
        .row{ margin: 0px !important; padding: 0px !important; width: 100%; position: relative; font-size: 12px;}
        .clear { clear: both;}
        h1{ margin: 0px; font-size: {{ $config['headings_length']}}px; }
        h2{ margin: 0px; font-size: {{  $config['text_length']}}px; }
        .title{ text-transform: uppercase; }
        .separator{ width: 100%; height: 30px; }
        .left{ float: left; }
        .right{ float: right;}
        .ticket_list{ border-top: 3px double black;border-bottom: 2px solid black; margin-top: 10px; }
        .ticket_header span{ font-size: {{ $config['text_length']}}px; }
        .article_row{ float: left; font-size: {{ $config['text_length'] }}px;}
        .units_row{width: 12%;max-width: 12%; float: left; font-size: {{ $config['text_length'] }}px;}
        .name_row{width: 50%;max-width: 50%; float: left; font-size: {{ $config['text_length'] }}px;}
        .price_row{width: 19%;max-width: 19%; float: left; text-align: left; font-size: {{ $config['text_length'] }}px;}
        .totals_row{width: 33%;max-width: 33%; float: left; text-align: center; font-size: {{ $config['text_length'] }}px;}
        .total{text-align: center; font-size: {{ $config['text_length'] }}px; margin-top: 5px;}
        .totals{text-align: right; font-size: {{ $config['text_length']}}px;}
        .totals div{width: 100%;}
        .totals div span.value{width: 90px;display: inline-block;text-align: right;}
        .print-button{ width: 100%; text-align: center;}
        .sf-toolbar{display: none;}

    </style>
</head>
<body>
<input type="hidden" name="_token" value="{{csrf_token()}}" id="token">
<div class="ticket">
    <div class="custom_row title">
       <label class="textlindo"> CIERRE DE CAJA</label>
    </div>
    <div style="margin-top: 5px; margin-bottom: 5px; border-top: 1px solid black;"></div>
    <div class="custom_row">
        Fecha de emisión
        <h1><label>{{ $close->creado }}</label></h1>
    </div>
    <div class="custom_row">
        Fecha Inicio
        <h1><label>{{ $close->fecha_inicio }}</label></h1>
    </div>
    <div class="row">
        Fecha Final
        <h1><label>{{ $close->fecha_final}}</label></h1>
    </div>
    <div class="row">
      Efectivo:
      <h1><label>{{ $close->efectivo}}</label></h1>
    </div>
    <div class="row">
        Tarjeta
        <h1><label>{{ $close->tarjeta}}</label></h1>
    </div>
    <div class="row">
         Total Venta:
        <h1><label>{{$close->total_caja}}</label></h1>
    </div>
    <div class="row">
        Devolucion efectivo
        <h1><label>{{ $close->devolucion_efectivo}}</label></h1>
    </div>
    <div class="row">
        Devolucion credito
        <h1><label>{{ $close->devolucion_credito}}</label></h1>
    </div>
    <div class="row">
        Total Devolucion: 
        <h1><label>{{ $close->devolucion_credito +  $close->devolucion_efectivo}}</label></h1>
    </div>
    <div class="row">
        Inicio en Caja
        <h1><label>{{ $close->inicio_caja}}</label></h1>
    </div>
    <div class="row">
        Total en Caja
        <h1><label>{{ ($close->inicio_caja + $close->total_caja) - ($close->devolucion_credito +  $close->devolucion_efectivo) }}</label></h1>
    </div>
    <div class="row">
        Ticktes contados.
        <h1><label>{{ $close->cantticket}}</label></h1>
    </div>
</div>
</body>
</html>