<!DOCTYPE html>
<html>
<head>
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

</head>
<body>
<input type="hidden" name="_token" value="{{csrf_token()}}" id="token">
<div class="ticket">
    <!-- EMPRESA -->
    <div class="row txt-center border">
        <h5 class="title">{{ $site['name'] }}</h5>
    </div>
    <div class="separator"></div>
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
        <h5>{{$site['phone'] }}</h5>
    </div>
    <div class="row">
       <h5> NIF: {{ $site['cif'] }}</h5>
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
    <div class="row">
        <h5>Ticket: {{ $ticket['code'] }}</h5>
        <h5>{{ \Carbon\Carbon::parse($ticket['created_at'])->format('d-m-Y H:i')}}</h5>
        <h5>Le atendio: {{  $site['atendido']}} </h5>
    </div>
    <div class="separator"></div>
    <div class="clear"></div>
    <div class="ticket_list">
        <div class="row">
            <div style="width: 40%; float: left">
                <b>Ferefencia (talla)</b>
            </div>
            <div  style="width: 20%;float: left">
                <b>Ud.</b>
            </div>
            <div  style="width: 20%;float: left">
                <b>PVP</b>
            </div>

            <div  style="width: 20%;float:right; text-align: right">
                <b>Total</b>
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
            <span style="margin-right: 40px">Total</span> : <b>{{ $ticket['fullimport'] }}</b>
        </div>
        <div class="row">
            <span style="margin-right: 17px">Recivido</span> : <b> {{ $ticket['received'] }}</b>
        </div>
        <div class="row">
            <span style="margin-right: 25px">Cambio</span> : <b> {{ $ticket['change'] }}</b>
        </div>
    </div>
    <div class="clear"></div>
    <div class="separator"></div>
    <div class="row txt-center title_pie">
        <p>Para cualquier cambio es imprescindible el ticket, excepto entregas, reservas y encargos, no se admiten devoluciones </p>
        <p>Valides 30 dias </p>
        @if ($config['mode'] == 'COPIA')
            <h5>{{$config['mode']}}</h5>
        @endif
    </div>


    <!-- Regalo -->
    @if ($gift == 1)
        <div class="separator"></div>
        <div class="clear"></div>
        <div class="ticket">
            <!-- EMPRESA -->
            <div class="row txt-center border">
                <h5 class="title">{{ $site['name'] }}</h5>
            </div>
            <div class="separator"></div>
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
                <h5>{{$site['phone'] }}</h5>
            </div>
            <div class="row">
                <h5> NIF: {{ $site['cif'] }}</h5>
            </div>
            <div class="separator"></div>
            <div class="row">
                <h5>Ticket: {{ $ticket['code'] }}</h5>
                <h5>{{ \Carbon\Carbon::parse($ticket['created_at'])->format('d-m-Y H:i')}}</h5>
                <h5>Le atendio: {{  $site['atendido']}} </h5>
            </div>
            <div class="separator"></div>
            <div class="clear"></div>
            <div class="ticket_list">
                <div class="row">
                    <div style="width: 50%; float: left">
                        <b>Ferefencia (talla)</b>
                    </div>
                    <div  style="width: 50%;float: left">
                        <b>Ud.</b>
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
                    <div class="clear"></div>
                @endforeach
            </div>
            <div class="clear"></div>
            <div class="row txt-center title_pie" style="font-size: 14px">
                REGALO
            </div>
            <div class="separator"></div>
            <div class="row txt-center title_pie">
                <p>Para cualquier cambio es imprescindible el ticket, excepto entregas, reservas y encargos, no se admiten devoluciones </p>
                <p>Valides 30 dias </p>
                @if ($config['mode'] == 'COPIA')
                    <h5>{{$config['mode']}}</h5>
                @endif
            </div>
    @endif
</div>
</div>
</body>
</html>