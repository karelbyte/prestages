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
                    <div  class="form-group input-group-sm">
                        <label class="label1">Nombre completo del usuario</label>
                        <input type="text" class="form-control" ng-model="entity.name" id="name" placeholder="Nombre y apellidos...">
                        <div ng-if="retorno.name"> <label class="retorno" ><%retorno.name[0]%></label></div>
                    </div>
                    <div  class="form-group input-group-sm">
                        <label class="label1">Usuario (Alias)</label>
                        <input type="text" class="form-control" ng-model="entity.nick" id="nick" placeholder="Alias con que accedera al sistema...">
                        <div ng-if="retorno.nick"> <label class="retorno" ><%retorno.nick[0]%></label></div>
                    </div>
                    <div  class="form-group input-group-sm">
                        <label class="label1">Codigo de vendedor</label>
                        <input type="text" class="form-control" ng-model="entity.code" id="code" placeholder="Código para gestionar ventas...">
                        <div ng-if="retorno.code"> <label class="retorno" ><%retorno.code[0]%></label></div>
                    </div>
                    <div  class="form-group input-group-sm">
                        <label class="label1">Correo electronico</label>
                        <input type="text" class="form-control" ng-model="entity.email" id="email" placeholder="email valido...">
                        <div ng-if="retorno.email"> <label class="retorno" ><%retorno.email[0]%></label></div>
                    </div>
                    <div  class="form-group input-group-sm">
                        <label class="label1">Contraseña</label>
                        <label ng-hide="modalstate != 'edit'" class="label1" style="float: right;"><input type="checkbox" ng-click="setpass(pass)"  ng-checked="pass"> Editar Contraseña</label>
                        <input  ng-disabled="!pass" type="password" class="form-control"  ng-model = "entity.password" placeholder="No menos de 6 caracteres...">
                        <div ng-if="retorno.password"> <label class="retorno"><%retorno.password[0]%></label></div>
                    </div>
                    <div  class="form-group input-group-sm">
                        <label class="label1">Confirmar - Contraseña</label>
                        <input  ng-disabled="!pass" type="password" class="form-control" ng-model = "entity.password_confirmation" placeholder="Reconfirma la contraseña...">
                        <div ng-if="retorno.password_confirmation"> <label class="retorno"><%retorno.password_confirmation[0]%></label></div>
                    </div>
                    <label style="margin-top: 15px;" >
                        <input type="checkbox" ng-checked="entity.active"  ng-model="entity.active">
                        Activo
                    </label>
                </div>
                <div class="modal-footer mdl_footer_fix">
                    <button type="button" class="btn btn-default btn-sm" id="btn-save" ng-click="save(modalstate, entity.id)">Guardar</button>
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>