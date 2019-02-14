<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="modal_ticket_edit">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header mdl-header_fix">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h5 class="modal-title" id="myModalLabel" style="color: white;"><%form_title%></h5>
            </div>
            <div class="modal-body mdl-body_fix ">
                <input type="hidden" name="_token" value="{{csrf_token()}}" id="token">
                <div class="row">
                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3" >
                        <label for="area" class="label1">Referencia</label><br>
                        <span class="textlindo"><%entity.code%></span><br><br>
                        <label for="area" class="label1">Codigo cliente</label> <br>
                        <span class="textlindo"><%entity.codeclient%></span><br><br>
                        <label for="area" class="label1">Nombre del cliente</label><br>
                        <span class="textlindo"><%entity.name%></span><br><br>
                        <label for="area" class="label1">Importe</label><br>
                        <span class="textlindo"><%entity.fullimport | currency : currencydef.sign + " "%></span> (<%entity.typepayment%>)<br><br>
                        <label for="area" class="label1">Creado</label><br>
                        <span class="textlindo"><%entity.created_at%></span><br><br>
                        <label for="area" class="label1">Estado</label><br>
                        <span class="textlindo"><%entity.descrip%></span><br><br>
                        <div ng-if="entity.owner">
                            <label for="area" class="label1">Facturado</label><br>
                            <span class="textlindo"><%entity.owner%></span><br>
                        </div>
                    </div>
                    <div class="col-xs-9 col-sm-9 col-md-9" style="border-left: 1px solid #9d9d9d">
                        <div class="row">
                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 "><label class="label1">EAN13</label></div>
                            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col_fix text-justify"><label class="label1">Descripción</label></div>
                            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-2 text-center"><label class="label1">Cantidad</label></div>
                            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-2 text-center"><label class="label1">Importe</label></div>
                            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-left"></div>
                        </div>

                        <div ng-repeat="x in listaventa track by $index">
                            <div class="row rowtable mouse div_hover">
                                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 "><label class="label1"  ng-class="{'tachado': x.status == 0}"><%x.ean13%></label></div>
                                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col_fix text-justify" style="font-size:12px"  ng-class="{'tachado': x.status == 0}"<label><%ifv(x.name, x.status)%></label> </div>
                                <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col_fix text-center " ><label class="label1" ng-class="{'tachado': x.status == 0}"><%x.cant%> </label></div>
                                <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-center"><label class="label1" style="color: blue" ng-class="{'tachado': x.status == 0}" ><%x.fullimport | currency : currencydef.sign+" "%></label></div>
                                <div ng-if="entity.status !== 5 && entity.status !== 10 && entity.status !== 11" class="col-xs-1 col-sm-1 col-md-1 col-lg-1  text-left">
                                   <div ng-if="x.status == 1 ">
                                        <button ng-hide="tickedit" class="btn btn-danger btn-xs" ng-click="deletedet(x)"><i class="fa fa-trash"></i></button>
                                    </div> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer mdl_footer_fix">
                <div class="row">
                    <div class="col-lg-1 text-left col_fix">
                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"  ng-click="print(entity.id, 'COPIA')">Imprimir</button>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                        <ul ng-if="tpage > 1" class="pagination">
                            <li ng-class="{disabled: currentp ==1}"><a href="" ng-click = "pageChanged(1)"> <span class="glyphicon glyphicon-step-backward" aria-hidden="true"></span></a></li>
                            <li ng-class="{disabled: currentp ==1}"><a href="" ng-click = "pageChanged(currentp - 1)"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span></a></li>
                            <li ng-repeat="page in paginar(tpage, currentp)" ng-class="{active:currentp == page}"><a href="" ng-click = "pageChanged(page)" ><%page%></a></li>
                            <li ng-class="{disabled: currentp == tpage}" ><a href=""  ng-click = "pageChanged(currentp + 1)"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></a></li>
                            <li ng-class="{disabled: currentp == tpage}"><a href="" ng-click = "pageChanged(tpage)"><span class="glyphicon glyphicon-step-forward" aria-hidden="true"></span></a></li>
                        </ul>
                    </div>
                    <div class="col-lg-offset-8">
                        <a ng-hide="tickedit" ng-class="{disabled : pase}" class="btn btn-info btn-sm" id="btn-save" ng-click="save()">Guardar</a>
                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" ng-click="close()">Cerrar</button>
                    </div>
                </div>

            </div>
    </div>
</div>