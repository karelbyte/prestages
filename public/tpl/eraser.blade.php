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
            </div>
            <div class="modal-footer mdl_footer_fix">
                <button ng-click="delete()" class="btn btn-danger btn-sm">SI</button>
                <a href="#" data-dismiss="modal" class="btn btn-default  btn-sm">No</a>
            </div>
        </div>
    </div>
   </div>
</div>
