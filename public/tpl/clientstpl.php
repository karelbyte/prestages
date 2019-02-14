<div class="modal fade " id="modal_add_edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="vertical-alignment-helper">
        <div class="modal-dialog vertical-align-center" role="document">
            <div class="modal-content">
                <div class="modal-header mdl-header_fix">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h5 class="modal-title" id="myModalLabel" style="color: white;"><%form_title%></h5>
                </div>
                <div class="modal-body mdl-body_fix ">
                    <input type="hidden" name="_token" value="{{csrf_token()}}" id="token">
                    <div class="row">
                    <div  class="form-group input-group-sm">
                        <label class="label1">Codigo</label>
                        <input type="text" class="form-control" ng-model="entity.code"  placeholder="código válido...">
                        <div ng-if="retorno.code"> <label class="retorno" ><%retorno.code[0]%></label></div>
                    </div>
                    <div  class="form-group input-group-sm">
                        <label class="label1">Nombre completo del cliente</label>
                        <input type="text" class="form-control" ng-model="entity.name"  placeholder="Nombre y apellidos...">
                        <div ng-if="retorno.name"> <label class="retorno" ><%retorno.name[0]%></label></div>
                    </div>
                    <div  class="form-group input-group-sm">
                        <label class="label1">Correo electronico</label>
                        <input type="email" class="form-control" ng-model="entity.email"  placeholder="email válido...">
                        <div ng-if="retorno.email"> <label class="retorno" ><%retorno.email[0]%></label></div>
                    </div>
                    <div  class="form-group input-group-sm">
                        <label for="note" class="label1">Nota</label>
                        <textarea class="form-control" ng-model="entity.note"  rows="5"></textarea>
                        <div ng-if="retorno.note"> <label class="retorno" ><%retorno.note[0]%></label></div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-5 col_fix">
                            <div  class="form-group input-group-sm">
                                <label class="label1">DNI/ CIF</label>
                                <input type="text" class="form-control" ng-model="entity.dni_cif" id="dni" placeholder="...">
                                <div ng-if="retorno.dni"> <label class="retorno" ><%retorno.dni_cif[0]%></label></div>
                            </div>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-1 col_fix"></div>
                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-6 col_fix">
                            <div  class="form-group input-group-sm">
                                <label class="label1">Telefono</label>
                                <input type="text" class="form-control" ng-model="entity.phone" placeholder="...">
                                <div ng-if="retorno.phone"> <label class="retorno" ><%retorno.phone[0]%></label></div>
                            </div>
                        </div>
                      </div>
                       <div  class="form-group input-group-sm">
                            <label class="label1">Direccion</label>
                            <input type="text" class="form-control" ng-model="entity.address"  placeholder="...">
                            <div ng-if="retorno.address"> <label class="retorno" ><%retorno.address[0]%></label></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col_fix">
                            <div  class="form-group input-group-sm">
                                <label class="label1">Provincia</label>
                                <input type="text" class="form-control" ng-model="entity.province"  placeholder="...">
                                <div ng-if="retorno.province"> <label class="retorno" ><%retorno.province[0]%></label></div>
                            </div>
                        </div>
                        <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                            <div  class="form-group input-group-sm">
                                <label class="label1">Localidad</label>
                                <input type="text" class="form-control" ng-model="entity.location" id="location" placeholder="...">
                                <div ng-if="retorno.location"> <label class="retorno" ><%retorno.location[0]%></label></div>
                            </div>
                        </div>
                        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col_fix">
                            <div  class="form-group input-group-sm">
                                <label class="label1">Codigo Postal</label>
                                <input type="text" class="form-control" ng-model="entity.zip" id="zip" placeholder="...">
                                <div ng-if="retorno.zip"> <label class="retorno" ><%retorno.zip[0]%></label></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer mdl_footer_fix">
                    <button type="button" class="btn btn-default btn-sm" id="btn-save" ng-click="save(modalstate, entity.id)">Guardar</button>
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>