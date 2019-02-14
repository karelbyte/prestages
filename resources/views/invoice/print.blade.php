<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Facturas</title>
    <style type="text/css">
        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        a {
            color: #0087C3;
            text-decoration: none;
        }

        header {
            padding: 10px 0;
            margin-bottom: 10px;
            border-bottom: 1px solid #AAAAAA;
        }

        #logo {
            float: left;
            margin-top: 8px;
        }

        #logo img {
            height: 70px;
        }

        #company {
            padding-right: 3px;
            float: right;
            text-align: right;
            border-right: 6px solid #0087C3;
        }
        #details {
            margin-bottom: 50px;
        }

        #client {
            padding-left: 6px;
            border-left: 6px solid #0087C3;
            float: left;
        }

        #client .to {
            color: #777777;
        }

        h2.name {
            font-size: 1.4em;
            font-weight: normal;
            margin: 0;
        }
        #invoice {
            float: right;
            text-align: right;
        }

        #invoice h1 {
            color: #0087C3;
            font-size: 2.4em;
            line-height: 1em;
            font-weight: normal;
            margin: 0  0 5px 0;
        }

        #invoice .date {
            font-size: 1.1em;
            color: #777777;
        }
        /* */
        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 10px;
        }

        table th,
        table td {
            padding: 5px;
            background: #EEEEEE;
            text-align: center;
            border-bottom: 1px solid #FFFFFF;
        }

        table th {
            white-space: nowrap;
            font-weight: normal;
        }

        table td {
            text-align: right;
        }

        table td h3{
            color: rgba(89, 219, 35, 0.34);
            font-size: 1.1em;
            font-weight: normal;
            margin: 0 0 0.2em 0;
        }

        table .no {
            color: #FFFFFF;
            font-size: 1.1em;
            background: #555555;
        }

        table .desc {
            text-align: left;
        }

        table .unit {
            background: #DDDDDD;
        }

        table .qty {
        }

        table .total {
            background: rgba(62, 159, 100, 0.49);
            color: #FFFFFF;
        }

        table td.unit,
        table td.qty,
        table td.total {
            font-size: 1.1em;
        }

        table tbody tr:last-child td {
            border: none;
        }


        table tfoot td {
            padding: 10px 10px;
            background: #FFFFFF;
            border-bottom: none;
            font-size: 1.2em;
            white-space: nowrap;
            border-top: 1px solid #AAAAAA;
        }

        table tfoot tr:first-child td {
            border-top: none;
        }

        table tfoot tr:last-child td {
            color: #1130b2;
            font-size: 1.4em;
            border-top: 1px solid #57B223;

        }

        table tfoot tr td:first-child {
           border-top: 1px solid #AAAAAA;
        }

        #thanks{
            font-size: 2em;
            margin-bottom: 50px;
        }

        #notices{
            padding-left: 6px;
            border-left: 6px solid #0087C3;
        }

        #notices .notice {
            font-size: 1.2em;
        }

        footer {
            color: #777777;
            width: 100%;
            height: 30px;
            position: absolute;
            bottom: 0;
           /* border-top: 1px solid #AAAAAA; */
            padding: 8px 0;
            text-align: center;
        }
    </style>
</head>
<body>
<header class="clearfix">
    <div id="logo">
        <img src="storage/{{$company->logo}}">
    </div>
    <div id="company">
        <div class="name">{{$company->name}}</div>
        <div class="address">{{$company->address}}</div>
        <div>{{$company->phone}}</div>
        <div  class="email">{{$company->email}}</div>
        <div class="email">CIF: {{$company->cif}}</div>
    </div>
    </div>
</header>

<div id="details" class="clearfix">
        <div id="client">
            <div class="to">FACTURADO A:</div>
            <h2 class="name">{{$client->name}}</h2>
            <div class="address">{{$client->address}}</div>
            <div class="email">{{$client->email}}</div>

        </div>
        <div id="invoice">
            <h1>FACTURA {{$invoice->numero}}</h1>
            <div class="date">REFERENCIA : {{$invoice->referencia}}</div>
            <div class="date">FECHA : {{$invoice->femision}}</div>
            <!-- <div class="date">Due Date: 30/06/2014</div> -->
        </div>
</div>
@if ( $invoice->status == 8)
    <label style="font-size: 25px; color: red;">CANCELADA</label>
@endif
<table border="0" cellspacing="0" cellpadding="0">
        <tr>
            <th class="no">#</th>
            <th class="desc">DESCRIPCION</th>
            <th class="unit">IMPORTE</th>
            <th class="qty">CANTIDAD</th>
            <th class="unit">%/IVA</th>
            <th class="qty">IVA/IMPORTE</th>
            <th class="total">TOTAL</th>
        </tr>

        <tbody>

        @foreach ($details as $index => $detail)
            <tr>
                <td class="no">{{$index+1}}</td>
                <td class="desc"><h3 style="color: #0e140a">{{$detail->code}}</h3></td>
                <td class="unit">{{$detail->price}}</td>
                <td class="qty">{{$detail->cant}}</td>
                <td class="unit">{{$detail->iva}}</td>
                <td class="qty">{{$detail->ivaimport}}</td>
                <td class="total">{{$detail->fullimport}}</td>
            </tr>
         @endforeach


                        <tr>
                            <td></td>
                            <td></td>
                            <td colspan="2"></td>
                            <td colspan="2">SUBTOTAL</td>
                            <td  class="unit">{{$subtotal}}</td>
                        </tr>
                        <tr>
                            <td ></td>
                            <td ></td>
                            <td colspan="2"></td>
                            <td colspan="2">SUBTOTAL IVA</td>
                            <td  class="unit">{{$iva}}</td>
                        </tr>
                        <tr>
                            <td> </td>
                            <td></td>
                            <td colspan="2"></td>
                            <td colspan="2">IMPORTE TOTAL</td>
                            <td  class="total">{{$invoice->importe}}</td>
                        </tr>
        </tbody>
</table>


                    <div class="address">Gracias por su preferencia!</div>
                    <!-- <div id="notices">
                         <div>NOTICE:</div>
                         <div class="notice">A finance charge of 1.5% will be made on unpaid balances after 30 days.</div>
                     </div> -->

<footer>

</footer>
</body>
</html>