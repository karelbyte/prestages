
<div class="modal fade " id="modal_product_detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="vertical-alignment-helper">
        <div class="modal-dialog vertical-align-center" role="document">
            <div class="modal-content">
                <div class="modal-header mdl-header_fix">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h5 class="modal-title" id="myModalLabel" style="color: white;">Detalles del producto</h5>
                </div>
                <div class="modal-body mdl-body_fix ">
                    <input type="hidden" name="_token" value="{{csrf_token()}}" id="token">
                    <div class="row">
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <input type="hidden" name="_token" value="{{csrf_token()}}" id="token">
                            <img ng-src="<%detail.image%>" alt="Description"  height="100%" width="100%"/>
                        </div>
                        <div class="col-xs-8 col-sm-8 col-md-8">
                           <label for="area" class="label1">Descripcion del producto</label> <br>
                           <span class="textlindo"><%entity.name%></span><br><br>
                           <label for="area" class="label1">Categoria</label> <br>
                           <span class="textlindo"><%entity.category%></span><br><br>
                           <label for="area" class="label1">Disponibilidad</label><br>
                           <span class="textlindo"><%detail.availability%></span><br><br>
                           <label for="area" class="label1">Stock</label><br>
                           <span class="textlindo"><%entity.stock%></span><br><br>
                           <label for="area" class="label1">Precio</label><br>
                           <span class="textlindo"><%entity.price%></span><br><br>
                            <label for="area" class="label1">Ean13</label><br>
                            <span class="textlindo"><%entity.ean13%></span><br>
                        </div>
                    </div>
                </div>
                <div class="modal-footer mdl_footer_fix">
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>