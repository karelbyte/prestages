@extends('layout')
@section('content')
<div ng-controller="closes_ctrl">
    <div class="row row_margin_boton">
        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col_fix">
          <button class="btn btn-info btn-sm" ng-click="boxopen()">Cambio en caja</button>
          <button class="btn btn-danger btn-sm" ng-click="closebox()">Cerrar Caja</button>
        </div>
        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-offset-6 col_fix">
            <eafilter field="name"></eafilter>
        </div>
    </div>
    <div id="modal_box"></div>
    <div class="panel panel-info">
        <div class="panel-heading">
            Historial de cierres.
        </div>
        <div class="row">
            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-justify"><label>Folio</label></div>
            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-justify"><eafieldorder display="Usuario" field="closes.iduser" idfs="iusers"></eafieldorder></div>
            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 "><eafieldorder display="Fecha" field="closes.creado" idfs="icreado"></eafieldorder></div>
            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-justify"><label>Importe</label></div>
            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-justify"><label>Inicio en Caja</label></div>
            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-justify"><eafieldorder display="F. Inicio" field="closes.fecha_inicio" idfs="iinicio"></eafieldorder></div>
            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-justify"><eafieldorder display="F. Final" field="closes.fecha_final" idfs="ifinal"></eafieldorder></div>
            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-justify"><label>Tickets</label></div>
            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 col_fix"><label>ACCIONES</label></div>
        </div>
        <div class="panel-body pnl_fix ">
            <div ng-repeat="entity in lista"  ng-class="{'selectedtr': entity.default == 1}"  ng-click="setClickedRow($index, entity.id)" class="row rowtable mouse div_hover">
                <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-justify"><%entity.id%></div>
                <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-justify"><%entity.nick%></div>
                <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-justify"><%entity.creado%></div>
                <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-justify" style="color: #0000C2"><%entity.total_caja | currency:''%> <%" " + currencydef.sign%></div>
                <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-justify"><%entity.inicio_caja | currency :''%> <%"  "+ currencydef.sign%></div>
                <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-justify" style="font-size: 10px"><%entity.fecha_inicio%></div>
                <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-justify" style="font-size: 10px"><%entity.fecha_final%></div>
                <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-justify"><%entity.cantticket%></div>
        
                <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 col_fix">
                    <div class="dropdown">
                        <button class="btn btn-default btn-xs dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            Acciones
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1" style="min-width: 100px">
                         <!--   <li><a ng-click="details(entity)">Detalles..</a></li> -->
                            <li><a ng-click="view(entity.id, 'COPIA')">Visualizar..</a></li>
                            <li><a ng-click="print(entity.id)">Imprimir..</a></li>
                            <li><a ng-click="kill(entity.id)">Cancelar..</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer panel-info">
            <div ng-include="paging"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-3 col-ms-offset-9 col-md-offset-9 col-lg-offset-9 col_fix text-right">
            <label id="msj"></label>
        </div>

    </div>

    <div class="modal fade " id="modal_delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="vertical-alignment-helper">
            <div class="modal-dialog modal-sm vertical-align-center" role="document">
                <div class="modal-content">
                    <div class="modal-header mdl-header_fix">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h5 class="modal-title" id="myModalLabel" style="color: white;"><%killname%></h5>
                    </div>
                    <div class="modal-body mdl-body_fix">
                        <input type="hidden" id="iddelete">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
                        <p>Cuidado! Esta acción es irreversible. Desea proceder?</p>
                    </div>
                    <div class="modal-footer mdl_footer_fix">
                        <button ng-click="delete()" class="btn btn-danger btn-sm">SI</button>
                        <a href="#" data-dismiss="modal" class="btn btn-default  btn-sm">No</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

<div class="modal fade " id="modal_caja" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="vertical-alignment-helper">
        <div class="modal-dialog modal-sm vertical-align-center" role="document">
            <div class="modal-content">
                <div class="modal-header mdl-header_fix">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h5 class="modal-title" id="myModalLabel" style="color: white;">Apertura</h5>
                </div>
                <div class="modal-body mdl-body_fix ">
                    <input type="hidden" name="_token" value="{{csrf_token()}}" id="token">
                    <label for="area" class="label1">Saldo</label>
                    <input class="form-control input-sm" ng-model="saldo">
                </div>
                <div class="modal-footer mdl_footer_fix">
                    <button type="button" class="btn btn-default btn-sm" id="btn-save" ng-click="savebox()">Guardar</button>
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>

    <div class="modal fade " id="print_dialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="vertical-alignment-helper">
            <div class="modal-dialog vertical-align-center" role="document">
                <div class="modal-content">
                    <div class="modal-header mdl-header_fix">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h5 class="modal-title" id="myModalLabel" style="color: white;">Imprimir Cierre</h5>
                    </div>
                    <div class="modal-body mdl-body_fix">
                        <div class="col-12-lg text-center" id="bodys">

                        </div>
                    </div>
                    <div class="modal-footer mdl_footer_fix">
                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade " id="print_view" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" style="z-index: 1060">
        <div class="vertical-alignment-helper">
            <div class="modal-dialog vertical-align-center" role="document">
                <div class="modal-content">
                    <div class="modal-header mdl-header_fix">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h5 class="modal-title" id="myModalLabel" style="color: white;">Cierre Actual</h5>
                    </div>
                    <div class="modal-body mdl-body_fix" style="height: 300px" id="view">


                    </div>
                    <div class="modal-footer mdl_footer_fix">
                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade " id="modal_closes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="vertical-alignment-helper">
            <div class="modal-dialog modal-md vertical-align-center" role="document" style="width: 420px">
                <div class="modal-content">
                    <div class="modal-header mdl-header_fix">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h5 class="modal-title" id="myModalLabel" style="color: white;">Generar cierre de caja.</h5>
                    </div>
                    <div class="modal-body mdl-body_fix ">
                        <input type="hidden" name="_token" value="{{csrf_token()}}" id="token">
                        <div  class="form-group input-group-sm">
                            <label class="label1">Ultimo cierre: </label>
                            <label class="textlindo" style="color: #316e47"><%actual%></label>
                        </div>
                        <hr class="hr_fix_5">
                        <div class="row">
                            <div class="col-lg-6 col_fix">
                                <label class="label1">Fecha Inicio</label>
                                <div class='input-group date input-group-sm' id='datetimepicker1'>
                                    <input type='text' class="form-control" id="finicio"  style="color: #953b39">
                                    <span class="input-group-addon">
                                         <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                </div>
                            </div>
                            <div class="col-lg-6  col_fix" style="padding-left: 5px">
                                <label class="label1">Fecha Final</label>
                                <div class='input-group date input-group-sm' id='datetimepicker2'>
                                    <input type='text' class="form-control" id="ffinal" >
                                    <span class="input-group-addon">
                                         <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                </div>
                            </div>
                        </div>
                        <hr class="hr_fix_5">
                       
                        <hr class="hr_fix_5">
                        <button type="button" class="btn btn-warning btn-sm" id="btn-save" ng-click="calcular_closes()">Calcular</button>
                        <hr class="hr_fix_5">
                        <div  class="form-group input-group-sm">
                            <label class="label1">Inicio en caja: </label>
                            <label class="textlindo" style="color: #0000C2"><%saldo| currency :''%> <%"  "+ currencydef.sign%></label>
                        </div>
                       
                        <div  class="form-group input-group-sm">
                            <label class="label1">Venta en Efectivo: </label>
                            <label class="textlindo" style="color: #0000C2"><%closedetails.efectivo| currency :''%> <%"  "+ currencydef.sign%></label>
                        </div>
                        <div  class="form-group input-group-sm">
                            <label class="label1">Venta en Credito: </label>
                            <label class="textlindo" style="color: #0000C2"><%closedetails.tarjeta| currency :''%> <%"  "+ currencydef.sign%></label>
                        </div>
                        <div  class="form-group input-group-sm">
                            <label class="label1">Total Devoluciones Credito: </label>
                            <label class="textlindo" style="color: red">-<%closedetails.cancel_credit | currency :''%> <%"  "+ currencydef.sign%></label>
                        </div>

                        <div  class="form-group input-group-sm">
                            <label class="label1">Total Devoluciones Efectivo: </label>
                            <label class="textlindo" style="color: red">-<%closedetails.cancel_cash | currency :''%> <%"  "+ currencydef.sign%></label>
                        </div>
                      
                        <div  class="form-group input-group-sm">
                            <label class="label1">Total Cierre: </label>
                            <label class="textlindo" style="color: #0000C2"><%closedetails.money_caja | currency :''%><%"  "+ currencydef.sign%></label><label style="color:red">  - 
                            <%closedetails.cancel_money | currency :''%> <%"  "+ currencydef.sign%> </label> = <label class="textlindo" style="color: blue"><%closedetails.caja_real | currency :''%> <%"  "+ currencydef.sign%> </label>
                             </label>
                             </div>

                             <div  class="form-group input-group-sm">
                            <label class="label1">Total Caja: </label>
                            <label class="textlindo" style="color: #0000C2"><%saldo| currency :''%> <%"  "+ currencydef.sign%></label> + 
                            <label class="textlindo" style="color: #0000C2"><%closedetails.caja_real | currency :''%> <%"  "+ currencydef.sign%> </label>
                            =  <label class="textlindo" style="color: #0000C2"><%closedetails.caja_real + saldo | currency :''%> <%"  "+ currencydef.sign%></label>
                             </div>
                        <hr>
                        <div  class="form-group input-group-sm">
                            <label class="label1">Ticket Contabilizados: </label>
                            <label class="textlindo" style="color: #0000C2"><%closedetails.cantticket%></label>
                        </div>
                        <hr class="hr_fix_5">
                        <div  class="form-group input-group-sm">
                            Saldo en caja despues de cierre?
                            <input class="form-control input-sm" ng-model="saldonew">
                        </div>
                    </div>
                    <div class="modal-footer mdl_footer_fix">
                        <div class="row">
                            <div class="col-lg-6 col-ms-6 col-md-6 text-left col_fix">
                                <button class="btn btn-info btn-sm" ng-click="preview()">Caja Actual</button>
                            </div>
                            <div class="col-lg-6 col-ms-6 col-md-6 text-right col_fix">
                                <button type="button" class="btn btn-success btn-sm" id="btn-save" ng-click="generarcierre()">Crear</button>
                                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade " id="view_dialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="vertical-alignment-helper">
            <div class="modal-dialog vertical-align-center" role="document">
                <div class="modal-content">
                    <div class="modal-header mdl-header_fix">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h5 class="modal-title" id="myModalLabel" style="color: white;">Visualizar Ticket</h5>
                    </div>
                    <div class="modal-body mdl-body_fix">
                        <div class="col-12-lg text-center" id="view" style="height: 300px">

                        </div>
                    </div>
                    <div class="modal-footer mdl_footer_fix">
                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
@section('scripts')
<script src="app/closes.js"></script>
@endsection