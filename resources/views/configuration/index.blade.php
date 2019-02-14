@extends('layout')
@section('content')
 <div ng-controller="config_clr">
     <div>
         <!-- Nav tabs -->
         <ul class="nav nav-tabs" role="tablist">
             <li role="presentation" class="active"><a href="#presta" aria-controls="presta" role="tab" data-toggle="tab">Prestashop</a></li>
             <li role="presentation"><a href="#empre" aria-controls="empre" role="tab" data-toggle="tab">Empresa</a></li>
             <li role="presentation"><a href="#currency" aria-controls="messages" role="tab" data-toggle="tab">Moneda</a></li>
             <li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Venta</a></li>
             <li role="presentation"><a href="#print" aria-controls="print" role="tab" data-toggle="tab">Impresión</a></li>
         </ul>
         <div id="modal_box"></div>
         <!-- Tab panes -->
         <div class="tab-content">
             <div role="tabpanel" class="tab-pane active" id="presta">
                 <div class="col-sm-5 col-md-5 col-lg-5 col_fix" >
                     <div class="panel panel-info" style="margin-top: 5px">
                        <div class="panel-heading pnl_header_fix">
                            Detalles de la conexión a la tienda en linea.
                        </div>
                     	<div class="panel-body">
                            <div  class="form-group input-group-sm" style="padding: 5px 5px 0 5px">
                                <label class="label1">Url de la tienda</label> <br>
                                <input type="text" class="form-control"  ng-model="presta.url" placeholder="http://sutienda.com">
                            </div>
                            <div  class="form-group input-group-sm" style="padding: 5px 5px 0 5px">
                                <label class="label1">Código del Servicio Web </label> <br>
                                <input type="text" class="form-control"  ng-model="presta.keycode">
                            </div>
                     	</div>
                         <div class="panel-footer">
                             <div class="row">
                             <div class="col-lg-10 col_fix">
                                 <button class="btn btn-info btn-sm" ng-click="addconect()"><i class="fa fa-check"></i>  Guardar</button>
                                 <button class="btn btn-warning btn-sm" ng-click="testconect()"><i class="fa fa-wifi"></i>  Probar conexión </button>
                                <!-- <button class="btn btn-success btn-sm" ng-click="getconect()"><i class="fa fa-refresh"></i>  Actualizar</button> -->
                             </div>
                            <!-- <div class="col-lg-2">
                                 <button class="btn btn-warning btn-sm" ng-click="getcache()"><i class="fa fa-database"></i>  Sincronizar</button>
                             </div> -->
                             </div>
                         </div>
                     </div>

                 </div>
             </div>
             <div role="tabpanel" class="tab-pane" id="empre">
                 <div class="col-sm-5 col-md-5 col-lg-5 col_fix" >
                     <div class="panel panel-info" style="margin-top: 5px">
                         <div class="panel-heading pnl_header_fix">
                             Generales de la empresa o tienda.
                         </div>
                         <div class="panel-body">
                             <div  class="form-group input-group-sm" style="padding: 5px 5px 0 5px">
                                 <label class="label1">Nombre</label> <br>
                                 <input type="text" class="form-control"  ng-model="company.name">
                             </div>
                             <div  class="form-group input-group-sm" style="padding: 5px 5px 0 5px">
                                 <label class="label1">Dirección</label> <br>
                                 <input type="text" class="form-control"  ng-model="company.address">
                             </div>
                             <div  class="form-group input-group-sm" style="padding: 5px 5px 0 5px">
                                 <label class="label1">email</label> <br>
                                 <input type="text" class="form-control"  ng-model="company.email">
                             </div>
                             <div  class="form-group input-group-sm" style="padding: 5px 5px 0 5px">
                                 <label class="label1">Telefono</label> <br>
                                 <input type="text" class="form-control"  ng-model="company.phone">
                             </div>
                             <div  class="form-group input-group-sm" style="padding: 5px 5px 0 5px">
                                 <label class="label1">CIF</label> <br>
                                 <input type="text" class="form-control"  ng-model="company.cif">
                             </div>
                             <div class="form-group input-group-sm text-center" style="padding: 5px 5px 0 5px">
                                 <div ng-show="uplogo">
                                     <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                     <span class="sr-only">Loading...</span>
                                 </div>
                                 <img ng-src="../../storage/<%imgrute%>" ng-click="setimg()" height="100" width="100" alt="click para cargar logo de la empresa, *.jpg, *.png" style="border: 1px solid grey" >
                                 <input type="file" name="file" style="display: none;" uploader-model="file" id="imgperson" accept="image/*">
                             </div>
                         </div>
                         <div class="panel-footer">
                             <button class="btn btn-info btn-sm" ng-click="addcompany()"><i class="fa fa-check"></i>  Guardar</button>
                          </div>
                     </div>

                 </div>
             </div>
             <div role="tabpanel" class="tab-pane" id="currency">
                 <div class="col-sm-5 col-md-5 col-lg-5 col_fix" >
                     <div class="panel panel-info" style="margin-top: 5px">
                         <div class="panel-heading pnl_header_fix">
                             Generales de la moneda a usar.
                         </div>
                         <div class="panel-body">
                             <div  class="form-group input-group-sm" style="padding: 5px 5px 0 5px">
                                 <label class="label1">Nombre</label> <br>
                                 <input type="text" class="form-control"  ng-model="currency.name">
                             </div>
                             <div  class="form-group input-group-sm" style="padding: 5px 5px 0 5px">
                                 <label class="label1">Codigo ISO</label> <br>
                                 <input type="text" class="form-control"  ng-model="currency.iso_code">
                             </div>
                             <div  class="form-group input-group-sm" style="padding: 5px 5px 0 5px">
                                 <label class="label1">Singno</label> <br>
                                 <input type="text" class="form-control"  ng-model="currency.sign">
                             </div>

                         </div>
                         <div class="panel-footer">
                             <button class="btn btn-warning btn-sm" ng-click="getcurrency()"><i class="fa fa-eur"></i> Moneda de prestashop </button>
                             <button class="btn btn-info btn-sm" ng-click="addcurrency()"><i class="fa fa-check"></i>  Guardar</button>

                         </div>
                     </div>

                 </div>
             </div>
             <div role="tabpanel" class="tab-pane" id="settings">
                 <div class="col-sm-5 col-md-5 col-lg-5 col_fix" >
                     <div class="panel panel-info" style="margin-top: 5px">
                         <div class="panel-heading pnl_header_fix">
                             Generales de una venta.
                         </div>
                         <div class="panel-body">
                             <form>
                             <div  class="form-group input-group-sm" style="padding: 5px 5px 0 5px">
                                 <label class="label1">Campo de precio modificable.</label> <br>
                                 <input type="radio" ng-model="priceset.modif" value="1" id="price" disabled> <label class="textlindo"> Precio</label><br>
                                 <input type="radio" ng-model="priceset.modif" value="2" id="import"> <label class="textlindo"> Importe</label>
                             </div>
                             </form>
                         </div>
                         <div class="panel-footer">
                             <button class="btn btn-info btn-sm" ng-click="setpricetap()"><i class="fa fa-check"></i>  Guardar</button>
                         </div>
                     </div>

                 </div>
             </div>
             <div role="tabpanel" class="tab-pane" id="print">
                 <div class="col-sm-5 col-md-5 col-lg-5 col_fix" >
                     <div class="panel panel-info" style="margin-top: 5px">
                         <div class="panel-heading pnl_header_fix">
                            Valores de impresion
                         </div>
                         <div class="panel-body">
                             <div  class="form-group input-group-sm" style="padding: 5px 5px 0 5px">
                                 <label class="label1">Productos por paginas</label> <br>
                                 <input type="text" class="form-control"  ng-model="print[0].value">
                             </div>
                             <div  class="form-group input-group-sm" style="padding: 5px 5px 0 5px">
                                 <label class="label1">Posicion de paginado</label> <br>
                                 <input type="text" class="form-control"  ng-model="print[1].value">
                             </div>
                             <div  class="form-group input-group-sm" style="padding: 5px 5px 0 5px">
                                 <label class="label1">Ancho del Ticket</label> <br>
                                 <input type="text" class="form-control"  ng-model="print[2].value">
                             </div>
                             <div  class="form-group input-group-sm" style="padding: 5px 5px 0 5px">
                                 <label class="label1">Altura de la descripcion del Ticket</label> <br>
                                 <input type="text" class="form-control"  ng-model="print[3].value">
                             </div>
                             <div  class="form-group input-group-sm" style="padding: 5px 5px 0 5px">
                                 <label class="label1">Altura del encabezado</label> <br>
                                 <input type="text" class="form-control"  ng-model="print[4].value">
                             </div>
                             <div  class="form-group input-group-sm" style="padding: 5px 5px 0 5px">
                                 <label class="label1">Tamaño del texto</label> <br>
                                 <input type="text" class="form-control"  ng-model="print[5].value">
                             </div>
                         </div>
                         <div class="panel-footer">
                             <button class="btn btn-info btn-sm" ng-click="setprint()"><i class="fa fa-check"></i>  Guardar</button>
                         </div>
                     </div>

                 </div>
             </div>
         </div>

     </div>


     <div class="modal fade " id="modal_cache" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
         <div class="vertical-alignment-helper">
             <div class="modal-dialog modal-sm vertical-align-center" role="document">
                 <div class="modal-content">
                     <div class="modal-header mdl-header_fix">
                      <h5 class="modal-title" id="myModalLabel" style="color: white;">Actualizando</h5>
                     </div>
                     <div class="modal-body mdl-body_fix">
                         <input type="hidden" id="iddelete">
                         <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
                         <p>Estamos actualizando la cache de productos, este proceso se hara en sugundo plano, no pulsar si ni es necesario. !!</p>
                     </div>
                     <div class="modal-footer mdl_footer_fix">
                         <div class="row">
                             <div class="col-md-6 text-left">
                                 <button class="btn btn-default btn-sm" data-dismiss="modal">Cerrar</button>
                             </div>
                             <div class="col-md-6 text-right">
                                 <i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>
                             </div>
                         </div>


                     </div>
                 </div>
             </div>
         </div>
     </div>

 </div>
@endsection
@section('scripts')
    <script src="app/config.js"></script>
@endsection