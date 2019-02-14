<div class="modal fade " id="modal_find" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
                        <label class="label1">Criterio</label>
                        <div class="input-group input-group-sm ">
                            <input type="text" class="form-control" placeholder="..." ng-model="criterio">
                            <span class="input-group-btn">
                             <button class="btn btn-default" type="button" ng-click="findproduct()"><i class="fa fa-search"></i></button>
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-justify"> <label class="label1">No</label></div>
                        <div class="col-xs-1 col-sm-1 col-md-1 col-lg-2 col_fix"><label class="label1">Referencia</label></div>
                        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-5 col_fix text-justify"><label class="label1">Descripción</label></div>
                        <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-left"></div>
                    </div>

                    <div ng-repeat="x in search track by $index">
                        <div class="row rowtable mouse div_hover">
                            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-justify"> <label class="label1"><%$index + 1%></label></div>
                            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-2 col_fix "><label class="label1"><%x.reference%></label></div>
                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-5 col_fix text-justify"><label><%x.name%></label> </div>
                            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1  text-left">
                                <button class="btn btn-danger btn-xs" ng-click="delete(x)"><i class="fa <i class="fa fa-check"></i>"></i></button>
                            </div>
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