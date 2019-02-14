<div class="modal fade " id="modal_delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="vertical-alignment-helper">
    <div class="modal-dialog modal-sm vertical-align-center" role="document">
        <div class="modal-content">
            <div class="modal-header mdl-header_fix">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h5 class="modal-title" id="myModalLabel" style="color: white;"><%killname%></h5>
            </div>
            <div class="modal-body mdl-body_fix">
                <input type="hidden" id="iddelete">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
                <p>Cuidado! Esta acción es irreversible. Desea proceder?</p>
                <hr>
                <label for=""> Se cobro en: <%pay%> </label><br>
                <form action="">
                <label for=""> Reembolso en: </label><br>
                 <input type="radio" name="o" ng-model="typede" value="cash" ng-click="paychange('cash')"> Efectivo<br>
                 <input type="radio" name="o"  ng-model="typede" value="credit"  ng-click="paychange('credit')"> Credito
                </form>
<hr>
                <label for="">Imprimir ticket abono? </label>
                

            </div>
            <div class="modal-footer mdl_footer_fix">
                <button ng-click="delete(1)" class="btn btn-info btn-sm">SI</button>
                <button ng-click="delete(2)" class="btn btn-info btn-sm">NO</button>
                <a href="#" data-dismiss="modal" class="btn btn-default  btn-sm">Cancelar</a>
            </div>
        </div>
    </div>
   </div>
</div>
