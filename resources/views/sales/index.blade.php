@extends('layout')
@section('content')
 <div ng-controller="sales_ctrl">
   <div class="row">
     <div class="col-sm-3 col-md-3 col-lg-3 col_fix_5">
         <div class="panel panel-info">
             <div class="panel-heading pnl_header_fix">
                 Generales de la venta 
             </div>
                <div class="panel-body pnl-body_fix">
                    <div class="col-sm-7 col-md-7 col-lg-7 col_fix_5">
                        <label class="label1">Código del cliente</label>
                        <div class="input-group input-group-sm ">
                            <input type="text" class="form-control" placeholder="codigo valido cliente.." ng-model="sale.codeclient">
                            <span class="input-group-btn">
                             <button class="btn btn-default" type="button" ng-click="findclient()"><i class="fa fa-search"></i></button>
                            </span>
                        </div>
                    </div>
                    <div class="col-sm-5 col-md-5 col-lg-5 col_fix_5">
                        <div class="form-group">
                            <label class="label1">Fecha de compra</label>
                            <div class='input-group date input-group-sm' id='datetimepicker1'>
                                <input type='text' class="form-control" ng-model="sale.date" >
                                <span class="input-group-addon">
                                 <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div  class="form-group input-group-sm" style="padding: 5px 5px 0 5px">
                        <label class="label1">Descripción</label> <br>
                        <input type="text" class="form-control"  ng-model="sale.name" disabled>
                    </div>

                    <div class="col-sm-12 col-md-12 col-lg-12 col_fix_5">
                        <div class="form-group input-group-sm">
                            <label class="label1">Vendedor</label>
                            <select class="form-control input-sm"  ng-model="codes.valor" >
                                <option ng-repeat="z in codes.availableOptions" value="<%z.id%>"><%z.code%></option>
                            </select>
                        </div>
                    </div>
                    <div style="padding: 0 5px 5px 5px">
                    <label class="label1">Código de barras (100 = "VARIOS")</label>
                    <div class="input-group input-group-sm" >
                        <input type="text" class="form-control mayuscula" id="scanbox" placeholder="escanear código de barras" ng-model="sale.codeEan13" ng-keypress="myFunct($event)">
                        <span class="input-group-btn">
                             <button class="btn btn-default"  ng-click="variosclick()"><i class="fa fa-shopping-basket"></i></button>
                             <button class="btn btn-danger"  ng-click="scancode()"><i class="fa fa-barcode"></i></button>
                            </span>
                    </div></div>
                    <div class="col-sm-4 col-md-4 col-lg-4 col_fix_5">
                        <div  class="form-group input-group-sm">
                            <label class="label1">Stock</label>
                            <input type="text" class="form-control"  ng-model="entity.stock"  disabled ng-class="{bakred: entity.stock <= 0}">
                        </div>
                    </div>
                    <div class="col-sm-4 col-md-4 col-lg-4 col_fix_5">
                        <div  class="form-group input-group-sm">
                            <label class="label1">IVA (%)</label>
                            <input type="text" class="form-control"  ng-model="entity.IVArate" id="iva" ng-keypress="keyiva($event)" tabindex="3" >
                        </div>
                    </div>
                    <div class="col-sm-4 col-md-4 col-lg-4 col_fix_5">
                        <div  class="form-group input-group-sm">
                            <label class="label1">Descuento (%)</label>
                            <input type="text" class="form-control"  ng-model="entity.discount" id="discount" ng-keypress="keydis($event)" tabindex="4"  > <!-- -->
                        </div>
                    </div>
                    <div class="col-sm-4 col-md-4 col-lg-4 col_fix_5">
                        <div  class="form-group input-group-sm">
                            <label class="label1" >Precio Ud. C/IVA</label>
                            <!-- ng-keypress="keyprice($event)" -->
                            <input class="form-control label1" ng-model="entity.price" id="price"  tabindex="1" > <!--  "-->

                        </div>
                    </div>
                    <div class="col-sm-4 col-md-4 col-lg-4 col_fix_5">
                        <div  class="form-group input-group-sm">
                            <label class="label1">Uds</label>
                            <input class="form-control"  ng-model="entity.amount" id="cantarticulos"  ng-keypress="keyamount($event)" tabindex="2" >
                        </div>
                    </div>

                    <div class="col-sm-4 col-md-4 col-lg-4 col_fix_5">
                        <div  class="form-group input-group-sm">
                            <label class="label1">TOTAL C/IVA</label>
                           <!-- <span class="form-control disabled">
                              <%entity.fullprice | currency : currencydef.sign+" "%>
                            </span>-->
                            <input type="text" class="form-control"  ng-model="entity.fullprice" id="full" ng-keypress="keyfull($event)" tabindex="5" >
                        </div>
                    </div>

                    <div  ng-hide="varios" class="col-sm-12 col-md-12 col-lg-12 col_fix_5">
                       <!-- <label for="area" class="label1"><%holas%></label> -->
                        <div  class="form-control" id="area"  style="height: 150px" >
                           <span class="textlindo"><%entity.name%></span>
                           <div  ng-bind-html="entity.description" ></div>
                        </div>

                    </div>
                    <div  ng-hide="!varios" class="col-sm-12 col-md-12 col-lg-12 col_fix_5" >
                        <label class="label1">Descripcion de producto</label>
                        <input type="text" class="form-control"  ng-model="entity.name" id="descrip" tabindex="6"  ng-keypress="setsale($event)">
                    </div>

                </div>
             <div class="panel-footer">
                 <div class="row">
                     <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col_fix"> <button class="btn btn-info btn-sm" ng-click="addsale()" id="adds"><i class="fa fa-plus"></i>  Agregar</button> </div>
                     <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col_fix"> <button class="btn btn-info  btn-sm" ng-click="buscar()"><i class="fa fa-search-plus"></i>  Buscar</button> </div>
                     <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col_fix">
                      <button class="btn btn-warning btn-sm" ng-click="productsdetails(entity.id, entity.type)"><i class="fa fa-list"></i>  Detalles</button>
                     </div>
                     <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col_fix text-center">
                         <div ng-show="eantime">
                             <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                             <span class="sr-only">Loading...</span>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>
     <div class="col-sm-9 col-md-9 col-lg-9 col_fix_5">
         <div class="panel panel-info">
             <div class="panel-heading pnl_header_fix">
                 Detalles de la venta
             </div>
             <div class="panel-body pnl-body_fix">
                 <div class="row">
                     <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-justify"> <label class="label1">No</label></div>
                     <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col_fix"><label class="label1">EAN13</label></div>
                     <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col_fix text-justify"><label class="label1">Descripción</label></div>
                     <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1  text-right"><label class="label1">Cantidad</label></div>
                     <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 col_fix text-left"><label class="label1">Precio</label></div>
                     <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2  text-left"><label class="label1">IVA / Import.</label></div>
                     <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 col_fix text-left"><label class="label1">Descuento</label></div>
                     <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 col_fix text-left"><label class="label1">Importe</label></div>
                     <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 col_fix text-right"><label></label></div>
                 </div>

                   <div ng-repeat="x in listaventa track by $index">
                     <div class="row rowtable mouse div_hover">
                         <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-justify"> <label class="label1"><%$index + 1%></label></div>
                         <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col_fix "><label class="label1"><%x.ean13%></label></div>
                         <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col_fix text-justify"><label><%x.name%></label> </div>
                         <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1  text-left"><label class="label1" style="color: red"><%x.amount%></label></div>
                         <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 col_fix text-left"><label class="label1" style="color: #0b421a"><%x.price_base  * x.amount | currency : ''%><%"  "+ currencydef.sign%></label></div>
                         <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-left"><label class="label1" style="color: #5e2400"><%x.IVArate %>% / <%x.IVAvalue| currency :''%> <%"  "+ currencydef.sign%></label></div>
                         <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 col_fix text-left"><label class="label1" style="color: #101010"><%x.discount%> %</label></div>
                         <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 col_fix text-left"><label class="label1" style="color: blue"><%x.fullprice | currency :''%> <%"  "+ currencydef.sign%></label></div>
                         <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1  text-center">
                             <button class="btn btn-danger btn-xs" ng-click="delete(x)"><i class="fa fa-trash"></i></button>
                         </div>
                     </div>
                 </div>
                 <div class="row" style="border-top: 3px solid grey">
                     <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-justify"></div>
                     <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 col_fix"></div>
                     <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col_fix text-right"><label class="label1">Total articulos</label></div>
                     <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-left"><label class="label1" style="color: #91090e; font-size: 15px"><%cantArticles%></label></div>
                     <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 col_fix text-right"><label class="label1">Total IVA</label></div>
                     <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-left"><label class="label1" style="color: #341900; font-size: 15px"><%totalIVA | currency :''%> <%"  "+ currencydef.sign%></label></div>
                     <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-right"><label class="label1">Total</label></div>
                     <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 col_fix text-left"><label class="label1" style="color: #000052; font-size: 15px"><%totalPrice | currency :''%> <%"  "+ currencydef.sign%></label></div>
                 </div>
             </div>
             <div class="panel-footer">
                 <div class="row">
                  <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                     <button class="btn btn-info btn-sm" ng-click="salesclose()"><i class="fa fa-plus"></i>  Vender</button>
                     <button class="btn btn-danger btn-sm" ng-click="salescancel()"><i class="fa fa-ban"></i>  Cancelar</button>
                  </div>
                     <div class="col-lg-offset-8">
                         <ul ng-if="tpage > 1" class="pagination">
                             <li ng-class="{disabled: currentp ==1}"><a href="" ng-click = "pageChanged(1)"> <span class="glyphicon glyphicon-step-backward" aria-hidden="true"></span></a></li>
                             <li ng-class="{disabled: currentp ==1}"><a href="" ng-click = "pageChanged(currentp - 1)"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span></a></li>
                             <li ng-repeat="page in paginar(tpage, currentp)" ng-class="{active:currentp == page}"><a href="" ng-click = "pageChanged(page)" ><%page%></a></li>
                             <li ng-class="{disabled: currentp == tpage}" ><a href=""  ng-click = "pageChanged(currentp + 1)"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></a></li>
                             <li ng-class="{disabled: currentp == tpage}"><a href="" ng-click = "pageChanged(tpage)"><span class="glyphicon glyphicon-step-forward" aria-hidden="true"></span></a></li>
                         </ul>
                     </div>
                 </div>
             </div>
         </div>
     </div>
   </div>

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
                                 <eafilter field="name"></eafilter>
                             </div>
                         </div>

                         <div class="row">
                             <div class="col-xs-2 col-sm-2 col-md-2 col-lg2 col_fix"><label class="label1">CODIGO</label></div>
                             <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 col_fix text-left"><label class="label1">NOMBRE</label></div>
                             <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 text-left col_fix"><label class="label1">CORREO</label></div>
                         </div>

                         <div ng-repeat="x in lista track by $index">
                             <div class="row rowtable mouse div_hover" ng-class="{'selectedtr_invo': x.check}" ng-click="paseclient(x)">
                                 <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col_fix "><label ><%x.code%></label></div>
                                 <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 col_fix text-justify"><label><%x.name%></label> </div>
                                 <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col_fix text-justify"><label><%x.email%></label></div>

                             </div>
                         </div>
                     </div>
                     <div class="modal-footer mdl_footer_fix">
                         <div class="row">
                             <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7 col_fix text-left">
                                 <div ng-include="paging"></div>
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

     <div ng-include="detailtpl"></div>
     <div class="modal fade " id="modal_sale" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
         <div class="vertical-alignment-helper">
             <div class="modal-dialog vertical-align-center" role="document" style="width: 400px">
                 <div class="modal-content">
                     <div class="modal-header mdl-header_fix">
                         <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                         <h5 class="modal-title" id="myModalLabel" style="color: white;">Finalizar venta</h5>
                     </div>
                     <div class="modal-body mdl-body_fix ">
                         <input type="hidden" name="_token" value="{{csrf_token()}}" id="token">
                         <div class="row">
                             <div class="col-md-4 col_fix_5 text-right"><label class="label1">Total a pagar</label></div>
                             <div class="col-md-8 col_fix_5"><label class="label1"><%totalPrice | currency :''%> <%"  "+ currencydef.sign%></label></div>
                         </div>
                         <br>
                         <div class="row ">
                             <div class="col-md-4 col_fix_5 text-right" style="margin-top: 8px"><label class="label1">Recibido</label></div>
                             <div class="col-md-8 col_fix_5"><input type="text" class="form-control"  id="recived" ng-model="recivido"></div>
                         </div>
                         <br>
                         <div class="row">
                             <div class="col-md-4 col_fix_5 text-right"><label class="label1">A devolver</label></div>
                             <div class="col-md-8 col_fix_5"><label class="label1" style="color: #7b1115"><%saldo | currency :''%> <%"  "+ currencydef.sign%></label></div>
                         </div>
                     </div>
                     <div class="modal-footer mdl_footer_fix">
                         <div class="btn-group">
                             <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                 <i class="fa fa-money"></i> Cobrar <span class="caret"></span>
                             </button>
                             <ul class="dropdown-menu">
                                 <li><a href="#"  ng-click="execsale({typepaiment: 1, tgift: 0}, true)">Imprimir Ticket</a></li>
                                 <li><a href="#" ng-click="execsale({typepaiment: 1, tgift: 0}, fase)">No imprimir</a></li>
                                 <li><a href="#" ng-click="execsale({typepaiment: 1, tgift: 1}, true)">Ticket Regalo</a></li>
                             </ul>
                         </div>
                         <div class="btn-group">
                             <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                 <i class="fa fa-cc"></i> Pago con tarjeta <span class="caret"></span>
                             </button>
                             <ul class="dropdown-menu">
                                 <li><a href="#"  ng-click="execsale({typepaiment: 2, tgift: 0}, true)">Imprimir Ticket</a></li>
                                 <li><a href="#" ng-click="execsale({typepaiment: 2, tgift: 0}, fase)">No imprimir</a></li>
                                 <li><a href="#" ng-click="execsale({typepaiment: 2, tgift: 1}, true)">Ticket Regalo</a></li>
                             </ul>
                         </div>
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


     <div class="modal fade" id="modal_find" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
         <div class="vertical-alignment-helper">
             <div class="modal-dialog vertical-align-center" role="document" style="width:50%">
                 <div class="modal-content">
                     <div class="modal-header mdl-header_fix">
                         <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                         <h5 class="modal-title" id="myModalLabel" style="color: white;"><%form_title%></h5>
                     </div>
                     <div class="modal-body mdl-body_fix ">
                         <input type="hidden" name="_token" value="{{csrf_token()}}" id="token">
                         <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-justify col_fix" style="margin-bottom: 5px">
                             Puede buscar el artículo por referencia, nombre, categorías, tags, o descripción. Ponga la palabra clave que desea buscar en el campo de debajo y espere los resultados.

                         </div>

                         <div  class="form-group input-group-sm">
                             <div style="margin-bottom:10px">
                             <label class="label1">Buscar por:</label>
                              <select class="form-control input-sm" ng-model="findfor" ng-options="option.name for option in selected_options">
    
                              </select>
                             </div>
                             <label class="label1">Criterio</label>
                             <div class="input-group input-group-sm ">
                                 <input type="text" class="form-control" placeholder="..." ng-model="criterio" ng-keypress="myFunctC($event)">
                                 <span class="input-group-btn">
                             <button class="btn btn-default" type="button" ng-click="findproduct()"><i class="fa fa-search"></i></button>
                            </span>
                             </div>
                         </div>

                         <div ng-show="rotate" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
                             <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                             <span class="sr-only">Loading...</span>
                         </div>
                         <div class="row" ng-show="header">
                             <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 text-justify"> <label class="label1">Codigo</label></div>
                             <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col_fix"><label class="label1">Referencia</label></div>
                             <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col_fix text-justify"><label class="label1">Descripción</label></div>
                             <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-left">Stock</div>
                             <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-left"></div>
                         </div>

                         <div ng-repeat="x in listabuscar track by $index">
                             <div class="row rowtable mouse div_hover">
                                 <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 text-justify"><%x.ean13%></div>
                                 <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col_fix "><%x.reference%></div>
                                 <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col_fix text-left"><%x.name%></div>
                                 <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-left">
                                 <button ng-show="x.id_default_combination == 0" class="btn btn-warning btn-xs"  ng-click="product_stock(x.id)"><i class="fa fa-ellipsis-h"></i></button>
                                 </div> 
                                 <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-left">
                                     <button ng-show="x.id_default_combination == 0" class="btn btn-info btn-xs"  ng-click="findproduct_forid(x.id)"><i class="fa fa-check"></i></button>
                                     <button ng-show="x.id_default_combination > 0" class="btn btn-success btn-xs"  ng-click="findcombinationfor(x)"><i class="fa fa-list-ul"></i></button>
                                 </div>
                             </div>
                         </div>

                         <!--cominaciones -->
                         <div ng-show="combinationfinds.length > 0">
                         <hr>
                         <div class="row"> 
                         Combinaciones de la referencia:<b> <%myreference%> </b> <br>
                         Producto:<b> <%myname%> </b>
                             <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7 text-justify"> <label class="label1">Nombre</label></div>
                             <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col_fix"><label class="label1">Stock</label></div>
                             <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-left"></div>
                         </div>
                         <div ng-repeat="com in combinationfinds track by $index">
                             <div class="row rowtable mouse div_hover">
                                 <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7 text-justify"><%getname(com)%></div>
                                 <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col_fix">
                                 <button ng-show="com.stock == 0" class="btn btn-warning btn-xs"  ng-click="combi_stock(com.idcomb)"><i class="fa fa-ellipsis-h"></i></button>
                                 <div ng-show="com.stock > 0 || com.stock == 'Ninguno'"> <%com.stock%></div> 
                                 </div>
                                 <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-left">
                                     <button  class="btn btn-info btn-xs"  ng-click="combi_select(com.idcomb)"><i class="fa fa-check"></i></button>
                                   </div>
                             </div>
                         </div>
                         </div>
                     </div>
                     <div class="modal-footer mdl_footer_fix">


                             <div class="row">
                                 <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                     <ul ng-if="tpagefind> 1" class="pagination">
                                         <li ng-class="{disabled: currentfind ==1}"><a href="" ng-click = "pageChangeds(1)"> <span class="glyphicon glyphicon-step-backward" aria-hidden="true"></span></a></li>
                                         <li ng-class="{disabled: currentfind ==1}"><a href="" ng-click = "pageChangeds(currentfind - 1)"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span></a></li>
                                         <li ng-repeat="pagef in paginarfind(tpagefind, currentfind)" ng-class="{active:currentfind == pagef}"><a href="" ng-click = "pageChangeds(pagef)" ><%pagef%></a></li>
                                         <li ng-class="{disabled: currentfind == tpage}" ><a href=""  ng-click = "pageChangeds(currentfind + 1)"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></a></li>
                                         <li ng-class="{disabled: currentfind == tpage}"><a href="" ng-click = "pageChangeds(tpagefind)"><span class="glyphicon glyphicon-step-forward" aria-hidden="true"></span></a></li>
                                     </ul>
                                 </div>
                                 <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 text-right col_fix">
                                     <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cerrar</button>

                                 </div>
                             </div>

                     </div>
                 </div>
             </div>
         </div>
     </div>

         <div id="modal_box"></div>

 </div>

@endsection
@section('scripts')
   <script src="app/sales.js"></script>


@endsection