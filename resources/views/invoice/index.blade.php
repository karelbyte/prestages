@extends('layout')
@section('content')
<div ng-controller="invoice_ctrl">
      <div class="row row_margin_boton">
          <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col_fix">
              <div ng-controller="invoice_create_ctrl">
                  <button  class="btn btn-info btn-sm" ng-click="toggle()"><i class="fa fa-edit fa-1x"></i> Crear</button>
                  <button  class="btn btn-success btn-sm" ng-click="refresh()"><i class="fa fa-refresh fa-1x"></i> Actualizar</button>
               <!--   <div ng-include="createtpl"></div> -->
                  <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="modal_invoice">
                      <div class="modal-dialog modal-lg" role="document">
                          <div class="modal-content">
                              <div class="modal-header mdl-header_fix">
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                  <h5 class="modal-title" id="myModalLabel" style="color: white;">Crear factura.</h5>
                              </div>
                              <div class="modal-body mdl-body_fix ">
                                  <input type="hidden" name="_token" value="{{csrf_token()}}" id="token">
                                  <div class="row">
                                      <div class="col-xs-3 col-sm-3 col-md-3" style="border-right: 1px solid #9d9d9d">
                                          <div class="form-group">
                                              <label class="label1">Fecha</label>
                                              <div class='input-group date input-group-sm' id='datetimepicker'>
                                                  <input type='text' class="form-control" id="femision" >
                                                  <span class="input-group-addon">
                                                  <span class="glyphicon glyphicon-calendar"></span>
                                                  </span>
                                              </div>
                                          </div>
                                          <div class="form-group">
                                              <label for="area" class="label1">Consecutivo</label><br>
                                              <span class="textlindo"><%fac.numero%></span><br>
                                          </div>
                                          <div class="form-group">
                                              <label for="area" class="label1">Referencia</label><br>
                                              <span class="textlindo" style="font-size: 8px"><%fac.referencia%></span><br>
                                          </div>
                                          <label class="label1">Codigo valido de Cliente</label>
                                          <div class="input-group input-group-sm ">
                                              <input type="text" class="form-control" ng-model="codeclient">
                                              <span class="input-group-btn">
                                                 <button class="btn btn-default" type="button" ng-click="findclient()"><i class="fa fa-search"></i></button>
                                               </span>
                                          </div>
                                          <br>
                                          <label for="area" class="label1">Importe</label><br>
                                          <span class="textlindo"><%fac.fullimport | currency :''%> <%"  "+ currencydef.sign%></span>
                                      </div>
                                      <div class="col-xs-9 col-sm-9 col-md-9" >

                                          <div class="row" style="border-bottom: 1px solid #316e47; margin-bottom: 15px; padding-bottom: 5px">
                                              <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 text-justify">
                                                  <label class="label1">LISTADO DE TICKETS DISPONIBLES</label>
                                              </div>
                                              <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col_fix">
                                                  <eafilter field="name"></eafilter>
                                              </div>
                                          </div>

                                          <div class="row">
                                              <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-justify"> <label class="label1">NO</label></div>
                                              <div class="col-xs-1 col-sm-1 col-md-2 col-lg-3 col_fix"><label class="label1">CODIGO</label></div>
                                              <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col_fix text-justify"><label class="label1">IMPORTE</label></div>
                                              <div class="col-xs-1 col-sm-1 col-md-1 col-lg-3 text-justify"><label class="label1">CREADO</label></div>

                                              <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-left"></div>
                                          </div>

                                          <div ng-repeat="x in tickets track by $index">
                                              <div class="row rowtable mouse div_hover" ng-class="{'selectedtr_invo': x.check}" ng-click="addtick(x)">
                                                  <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-justify"> <label ><%x.id%></label></div>
                                                  <div class="col-xs-1 col-sm-1 col-md-2 col-lg-3 col_fix "><label ><%x.code%></label></div>
                                                  <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col_fix text-justify"><label><%x.fullimport | currency :''%> <%"  "+ currencydef.sign%></label> </div>
                                                  <div class="col-xs-1 col-sm-1 col-md-1 col-lg-3 col_fix text-justify"><label><%x.created_at%></label></div>
                                                  <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1  text-left">
                                                      <!-- <input type="checkbox" class="form-control input-sm" style="margin-top: -5px"  ng-checked="x.check" ng-click="addtick(x)"> -->
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <div class="modal-footer mdl_footer_fix">
                                  <div class="row">
                                      <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">

                                      </div>
                                      <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-left">
                                          <div ng-include="paging"></div>
                                      </div>
                                      <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col_fix">
                                          <button type="button" class="btn btn-info btn-sm" id="btn-save" ng-click="generar()">Generar</button>
                                          <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cerrar</button>
                                      </div>

                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>

               <!--- modal de buscar clientes -->
                  <div class="modal fade " id="find_client" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                      <div class="vertical-alignment-helper">
                          <div class="modal-dialog vertical-align-center" role="document">
                              <div class="modal-content">
                                  <div class="modal-header mdl-header_fix">
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                      <h5 class="modal-title" id="myModalLabel" style="color: white;">Buscar clientes</h5>
                                  </div>
                                  <div class="modal-body mdl-body_fix">
                                      <div class="row" style="border-bottom: 1px solid #316e47; margin-bottom: 15px; padding-bottom: 5px">
                                          <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 text-justify">
                                          </div>
                                          <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col_fix">
                                              <div class="input-group input-group-sm ">
                                                  <input type="text" class="form-control" placeholder="codigo valido cliente.." ng-model="filtercli.name">
                                                  <span class="input-group-btn">
                                                 <button class="btn btn-default"><i class="fa fa-search"></i></button>
                                               </span>
                                              </div>
                                          </div>
                                      </div>

                                      <div class="row">
                                          <div class="col-xs-2 col-sm-2 col-md-2 col-lg-3"><label class="label1">CODIGO</label></div>
                                          <div class="col-xs-5 col-sm-5 col-md-5 col-lg-6  text-left"><label class="label1">NOMBRE</label></div>
                                          <div class="col-xs-4 col-sm-4 col-md-4 col-lg-3 text-left"><label class="label1">DNI/CIF</label></div>
                                      </div>

                                      <div ng-repeat="x in clientes track by $index">
                                          <div class="row rowtable mouse div_hover" ng-class="{'selectedtr_invo': x.check}" ng-click="paseclient(x)">
                                              <div class="col-xs-2 col-sm-2 col-md-2 col-lg-3  "><%x.code%></div>
                                              <div class="col-xs-5 col-sm-5 col-md-5 col-lg-6  text-justify"><%x.name%> </div>
                                              <div class="col-xs-4 col-sm-4 col-md-4 col-lg-3 text-justify"><%x.dni_cif%></div>

                                          </div>
                                      </div>
                                  </div>
                                  <div class="modal-footer mdl_footer_fix">
                                      <div class="row">
                                          <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7 col_fix text-left">
                                              <ul ng-if="totalpagecli > 1" class="pagination">
                                                  <li ng-class="{disabled: currentpagecli ==1}"><a href="" ng-click = "setpagecli(1)"> <span class="glyphicon glyphicon-step-backward" aria-hidden="true"></span></a></li>
                                                  <li ng-class="{disabled: currentpagecli ==1}"><a href="" ng-click = "setpagecli(currentpagecli - 1)"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span></a></li>
                                                  <li ng-repeat="pagecli in rangocli(totalpagecli ,currentpagecli )" ng-class="{active:currentpagecli == pagecli}"><a href="" ng-click = "setpagecli(pagecli)" > <%pagecli%></a></li>
                                                  <li ng-class="{disabled: currentpagecli == totalpagecli}" ><a href=""  ng-click = "setpagecli(currentpagecli + 1)"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></a></li>
                                                  <li ng-class="{disabled: currentpagecli == totalpagecli}"><a href="" ng-click = "setpagecli(totalpagecli)"><span class="glyphicon glyphicon-step-forward" aria-hidden="true"></span></a></li>
                                              </ul>
                                          </div>
                                          <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 col_fix text-right">
                                              <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cerrar</button>
                                          </div>

                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
               <!-- -->
              </div>
          </div>
          <div class="col-xs-3 col-sm-3 col-md-3 col-lg-offset-6 col_fix">
              <eafilter field="name"></eafilter>
          </div>
      </div>

          <div class="panel panel-info">
            <div class="panel-heading">
                Listado de facturas
            </div>
              <div class="row">
                  <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-justify"><eafieldorder display="NUM." field="code" idfs="icode"></eafieldorder></div>
                  <div class="col-xs-3 col-sm-3 col-md-3 col-lg-2 "><eafieldorder display="REFERENCIA" field="name" idfs="iname"></eafieldorder></div>
                  <div class="col-xs-3 col-sm-3 col-md-3 col-lg-2 text-justify"><label>CLIENTE</label></div>
                  <div class="col-xs-1 col-sm-1 col-md-2 col-lg-2 text-justify"><label>IMPORTE</label></div>
                  <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-justify"><eafieldorder display="CREADO" field="created_at" idfs="icreate"></eafieldorder></div>
                  <div class="col-xs-1 col-sm-1 col-md-2 col-lg-2 text-justify"><label>ESTADO</label></div>
                  <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 col_fix"></div>
              </div>
              <div class="panel-body pnl_fix ">
                  <div ng-repeat="entity in lista"  ng-class="{'selectedtr_invo': entity.descrip == 'CANCELADA'}"  ng-click="setClickedRow($index, entity.id)" class="row rowtable mouse div_hover">
                      <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-justify"><%entity.numero%></div>
                      <div class="col-xs-3 col-sm-3 col-md-3 col-lg-2 text-justify"><%entity.referencia%></div>
                      <div class="col-xs-3 col-sm-3 col-md-3 col-lg-2 text-justify"><%entity.name%></div>
                      <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-justify"><%entity.importe | currency :''%> <%"  "+ currencydef.sign%></div>
                      <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-justify"><%entity.created_at%></div>
                      <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-justify"><%entity.descrip%></div>
                      <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 col_fix">
                         <div class="dropdown">
                              <button class="btn btn-default btn-xs dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                   Acciones
                                  <span class="caret"></span>
                              </button>
                              <ul class="dropdown-menu" aria-labelledby="dropdownMenu1" style="min-width: 100px">
                                  <li><a ng-click="edit(entity)">Editar..</a></li>
                                  <li><a ng-click="setkill('Cancelar factura.', entity)" data-toggle='modal' data-target='#modal_delete'>Cancelar</a></li>
                                  <li><a ng-click="prints(entity.id)">Imprimir</a></li>
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
    <!-- imprimir -->
    <div class="modal fade " id="modal_print" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="vertical-alignment-helper">
            <div class="modal-dialog vertical-align-center modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header mdl-header_fix">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h5 class="modal-title" id="myModalLabel" style="color: white;">Impresión de facturas.</h5>
                    </div>
                    <div class="modal-body mdl-body_fix" id="pdf" style="height: 500px">

                    </div>
                    <div class="modal-footer mdl_footer_fix">
                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div ng-include="createtpl"></div>
    <div ng-include="erasertpl"></div>
@endsection
@section('scripts')
    <script src="app/invoice.js"></script>
        <script src="app/invoice_create.js"></script>
@endsection