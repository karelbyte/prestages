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
                                <input type='text' class="form-control" ng-model="fac.date" >
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
                            <span class="textlindo"><%fac.referencia%></span><br>
                        </div>
                        <div class="form-group input-group-sm">
                            <label class="label1">Cliente</label>
                            <select class="form-control input-sm"  ng-model="client.valor" >
                                <option ng-repeat="z in client.availableOptions" value="<%z.id%>"><%z.name%></option>
                            </select>
                        </div>
                        <div class="form-group input-group-sm">
                            <label class="label1">Forma de pago</label>
                            <select class="form-control input-sm"  ng-model="formapago.valor" >
                                <option ng-repeat="zd in formapago.availableOptions" value="<%zd.id%>"><%zd.fpago%></option>
                            </select>
                        </div>
                        <label for="area" class="label1">Importe</label><br>
                        <span class="textlindo"><%fac.fullimport | currency : currencydef.sign + " "%></span>
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
                                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col_fix text-justify"><label><%x.fullimport | currency : currencydef.sign+" "%></label> </div>
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