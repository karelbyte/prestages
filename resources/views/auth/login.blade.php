<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>PrestaGes pasaporte.</title>
    <!-- Estilos  //<body style="background-color: #0e6469"> -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/main.css" rel="stylesheet">


</head>
<body background="../storage/back.jpg">
<div class="container">
    <div class="modal fade " id="modal_login" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
        <div class="vertical-alignment-helper">
            <div class="modal-dialog vertical-align-center" role="document" style="width: 400px">
                <div class="modal-content">
                    <div class="modal-header mdl-header_fix">
                       <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
                        <h5 class="modal-title" id="myModalLabel" style="color: white;">PrestaGes</h5>
                    </div>
                    <div class="modal-body mdl-body_fix ">
                    <form action="/auth/login" method="post">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <label>Usuario:</label>
                        <input class="form-control input-sm" style="text-shadow: 1px 1px 1px rgba(0,0,0,0.3);" id="nick" name="nick" value = "admin1234">
                    </div>
                    <div class="form-group">
                        <label>Clave:</label>
                        <input type="password" class="form-control input-sm" id="password" name="password" value="1234">
                    </div>

                    </div>
                    <div class="modal-footer mdl_footer_fix">
                        <div class="row">
                            <div class="col-md-6 text-left">
                                <button type="submit" class="btn btn-default btn-sm" style="margin-bottom: 3px">Acceder</button>
                            </div>

                            <div class="col-md-6 text-right">
                                <a class="btn btn-default btn-sm" href="javascript:window.close();">Salir</a>
                            </div>
                        </div>

                        @if(session('message.login.error'))

                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <label style="font-size: 11px" class="textlindo">{{ session('message.login.error') }}</label>
                            </div>
                        @endif
                    </div>
                    </form>
                </div>

            </div>
        </div>
    </div>


</div>
<!-- Scripts -->
<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/login.js"></script>
</body>
</html>

