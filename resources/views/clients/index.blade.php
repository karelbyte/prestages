@extends('layout')
@section('content')
<div ng-controller="clients_ctrl">
      <div class="row row_margin_boton">
          <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col_fix">
             <button  class="btn btn-info btn-sm" ng-click="toggle('add', null)"><i class="fa fa-edit fa-1x"></i>&nbsp;Añadir</button>
          </div>
          <div class="col-xs-3 col-sm-3 col-md-3 col-lg-offset-6 col_fix">
              <eafilter field="name"></eafilter>
          </div>
      </div>

          <div class="panel panel-info">
            <div class="panel-heading">
                Listado de clientes.
            </div>
              <div class="row">
                  <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-justify"><eafieldorder display="CODI." field="code" idfs="icode"></eafieldorder></div>
                  <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 "><eafieldorder display="NOMBRE" field="name" idfs="iname"></eafieldorder></div>
                  <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 text-justify"><label>EMAIL</label></div>
                  <div class="col-xs-1 col-sm-1 col-md-2 col-lg-2 text-justify"><label>DNI /CIF</label></div>
                  <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-justify"><eafieldorder display="CREADO" field="created_at" idfs="icreate"></eafieldorder></div>
                  <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 col_fix"></div>
              </div>
              <div class="panel-body pnl_fix ">
                  <div ng-repeat="entity in lista"  ng-class="{'selectedtr': entity.default == 1}"  ng-click="setClickedRow($index, entity.id)" class="row rowtable mouse div_hover">
                      <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-justify"><%entity.code%></div>
                      <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 text-justify"><%entity.name%></div>
                      <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 text-justify"><%entity.email%></div>
                      <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-justify"><%entity.dni_cif%></div>
                      <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-justify"><%entity.created_at%></div>
                      <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 col_fix">
                         <div class="dropdown">
                              <button class="btn btn-default btn-xs dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                   Acciones
                                  <span class="caret"></span>
                              </button>
                              <ul class="dropdown-menu" aria-labelledby="dropdownMenu1" style="min-width: 100px">
                                  <li><a ng-click="toggle('edit', entity.id)">Editar..</a></li>
                                  <li><a ng-click="setkill('Eliminar cliente.', entity.id)" data-toggle='modal' data-target='#modal_delete'>Eliminar</a></li>
                                  <li><a ng-click="setdef(entity.id)">Defecto</a></li>
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
    <div id="modal_box"></div>
        <div ng-include="createtpl"></div>
        <div ng-include="erasertpl"></div>
</div>
@endsection
@section('scripts')
    <script src="app/clients.js"></script>
@endsection