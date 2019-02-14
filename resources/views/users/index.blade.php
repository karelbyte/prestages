@extends('layout')
@section('content')

<div ng-controller="users_ctrl">
      <div class="row row_margin_boton">
          <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col_fix">
             <button  class="btn btn-info btn-sm" ng-click="toggle('add', null)"><i class="fa fa-edit fa-1x"></i>&nbsp;Añadir</button>
          </div>
          <div class="col-xs-3 col-sm-3 col-md-3 col-lg-offset-6 col_fix">
              <eafilter field="name"></eafilter>
          </div>
      </div>
    <div id="modal_box"></div>
          <div class="panel panel-info">
            <div class="panel-heading">
                Listado de usuarios del sistema
            </div>
              <div class="row">
                  <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 "><eafieldorder display="NOMBRE" field="name" idfs="iname"></eafieldorder></div>
                  <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-justify"><eafieldorder display="USUARIO" field="nameuser" idfs="iuser"></eafieldorder></div>
                  <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 text-justify"><label>EMAIL</label></div>
                  <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-justify"><eafieldorder display="ULTIMA ENTRADA" field="lastlogin" idfs="ifecha"></eafieldorder></div>
                  <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col_fix"><label>ACCIONES</label></div>
              </div>
              <div class="panel-body pnl_fix ">
                  <div ng-repeat="entity in lista"  ng-class="{'selectedtr':$index == selectedRow}"  ng-click="setClickedRow($index, entity.id)" class="row rowtable mouse div_hover">
                      <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 text-justify"><%entity.name%></div>
                      <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-justify"><%entity.nick%></div>
                      <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 text-justify"><%entity.email%></div>
                      <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-justify"><%entity.lastlogin%></div>
                      <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col_fix">
                          <button class="btn btn-default btn-xs" ng-click="toggle('edit', entity.id)"> <span class="glyphicon glyphicon-edit"></span></button>
                          <button class="btn btn-danger btn-xs" ng-click="setkill('Eliminar usuario.',entity.id)" data-toggle='modal' data-target='#modal_delete'> <span class="glyphicon glyphicon-trash"></span></button>
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
        <div ng-include="createtpl"></div>
        <div ng-include="erasertpl"></div>
</div>
@endsection
@section('scripts')
    <script src="app/users.js"></script>
@endsection