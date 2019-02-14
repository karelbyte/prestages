@extends('layout')
@section('content')
<div ng-controller="tickets_ctrl">
    <input type="hidden" ng-model="h"  ng-init="h = 'actual'">
    <div class="row row_margin_boton">
        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col_fix">

        </div>
        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-offset-6 col_fix">

        </div>
    </div>
    <div id="modal_box"></div>
    <div class="panel panel-info">
        <div class="panel-heading">
            Listado de tickets.
        </div>
        <div class="row" style="padding-top: 5px; padding-bottom: 3px">
        
            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-justify col_fix_5"><eafilter field="code" caret="off"></eafilter></div>
            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 "></div>
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 text-justify"> <eafilter field="name" caret="off"></eafilter></div>
           <!-- <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-justify"></div> -->
            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-justify"><input style="margin-right:5px" type="checkbox" class="form-controll" ng-model='filter.hoy'>Hoy</label></div>
            <!--<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-justify">Referencia</label></div>-->
            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-justify">
                <select class="form-control input-sm" ng-model="filter.status">
                <option value="">TODOS</option>
                <option ng-repeat ="st in statuslist" value="<%st.id%>"><%st.descrip%></option>
                </select>
            </div>

            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-justify">
                <select class="form-control input-sm" ng-model="filter.idclose">
                <option value="">TODOS</option>
                <option  value="0">ABIERTO</option>
                <option  value="1">CERRADO</option>

                </select>
            </div>
           
            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 col_fix"><label for=""></div>
        </div>
        <div class="row" style="border-top: 1px solid rgba(204, 204, 204, 0.55); padding-top: 3px" >
            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-justify"><eafieldorder display="Folio" field="tickets.code" idfs="iticketcode"></eafieldorder></div>
            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 "><eafieldorder display="Cód. Cliente" field="tickets.codeclient" idfs="icodeclient"></eafieldorder></div>
            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-justify"><label>Nombre Cliente</label></div>
            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-justify"><label>Importe</label></div>
            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-justify">
            <eafieldorder display="Creado" field="tickets.created_at" idfs="icreate"></eafieldorder>
            </div>
            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-justify"><label>Referencia</label></div>
            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-justify"><label>Estado</label></div>
            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 col_fix"></div>
        </div>
        <div class="panel-body pnl_fix ">
            <div ng-repeat="entity in lista"   ng-class="{'selectedtr_invo': entity.descrip == 'CANCELADO'}"  ng-click="setClickedRow($index, entity.id)" class="row rowtable mouse div_hover">
                <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-justify"><%entity.code%></div>
                <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-justify"><%entity.codeclient%></div>
                <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-justify"><%entity.name%></div>
                <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-justify"><%entity.fullimport | currency :''%> <%"  "+ currencydef.sign%></div>
                <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-justify"><%entity.created_at%></div>
          
                <a ng-click="buscatickcancel(entity.owner)"><div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-justify"><%entity.descrip%> <%entity.owner %></div></a>
                <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-justify">
                <div class="clabierto" ng-show="entity.idclose === 0">Abierto </div>
                <div class="clcerrado" ng-show="entity.idclose != 0">Cerrado </div>
                </div>
                <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 col_fix">
                    <div class="dropdown">
                        <button class="btn btn-default btn-xs dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            Acciones
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1" style="min-width: 100px">
                            <li ><a ng-click="details(entity, false)">Detalles..</a></li>
                            <li><a ng-click="view(entity.id, 'COPIA')">Visualizar</a></li>
                            <li><a ng-click="print(entity.id, 'COPIA')">Imprimir</a></li>
                            <li ng-if="entity.status !== 5 && entity.status !== 10 && entity.status !== 11"><a ng-click="cancel(entity)">Cancelar..</a></li>
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

    <div class="modal fade " id="print_dialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="vertical-alignment-helper">
            <div class="modal-dialog vertical-align-center" role="document">
                <div class="modal-content">
                    <div class="modal-header mdl-header_fix">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h5 class="modal-title" id="myModalLabel" style="color: white;">Imprimir Ticket</h5>
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
    <div ng-include="createtpl"></div>
    <div ng-include="eraser_ticktpl"></div>
</div>
@endsection
@section('scripts')
<script src="app/tickets.js"></script>
@endsection