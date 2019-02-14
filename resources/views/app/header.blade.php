<div ng-controller="header_ctrl">
    <nav class="navbar navbar-fixed-top navbar-inverse" role="navigation">
        <!-- <div class="container"> -->
        <div class="navbar-header" style="padding-left: 5px">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand nbarcolor" href="#" ng-click="abaut()">PrestaGes</a>
        </div>

        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <!-- Catalogos -->
            <ul class="nav navbar-nav navbar-left">
                <!-- <li><a href="/"><i class="fa fa-home"></i>&nbsp;Inicio</a></li> -->
                <li ><a href="/sales" style="color: orange"><i class="fa fa-shopping-cart"></i>&nbsp;Nueva Venta</a></li>

                <!-- Sistema -->
                <li ><a href="/tickets"><i class="fa fa fa-file-text-o"></i>&nbsp;Tickets</a></li>
                <!--<ul class="nav navbar-nav navbar-left">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ticket"></i> Ticket <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="/tickets"><i class="fa fa-file-text-o"></i>  Actuales</a></li>
                            <li><a href="/ticketsh"><i class="fa fa-list-alt"></i>  Todos</a></li>
                        </ul>
                    </li>

                </ul> -->
                <li><a href="/closes"><i class="fa fa-clock-o"></i>  Cierres</a></li>
                <li><a href="/invoices"><i class="fa fa-newspaper-o"></i>&nbsp;Facturas</a></li>
                <li><a href="/clients"><i class="fa fa-users"></i>&nbsp;Clientes</a></li>
             
                </ul>
            <!-- <ul class="nav navbar-nav navbar-left">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Informes&nbsp;&nbsp;<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="/users"><i class="fa fa-clock-o"></i>&nbsp;&nbsp;Cierres</a></li>
                        <li><a href="/"><i class="fa fa-cogs"></i>&nbsp;&nbsp;...</a></li>
                        <li><a href="/"><i class="fa fa-power-off"></i>&nbsp;&nbsp;....</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a class="fa fa-info" href="#" ng-click="abaut()">&nbsp;...</a></li>
                    </ul>
                </li>

              
            </ul> -->

            <!-- Sistema -->
            <ul class="nav navbar-nav navbar-right" style="padding-right: 30px">
                <li>  <p class="navbar-text usuario"></p></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Sistema&nbsp;&nbsp;<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#"><i class="fa fa-user"></i>&nbsp;&nbsp; {{Auth::user()->name}} </a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="/users"><i class="fa fa-user-plus"></i>&nbsp;&nbsp;Usuarios</a></li>
                        <li><a href="/config"><i class="fa fa-cogs"></i>&nbsp;&nbsp;Configuraciones</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a class="fa fa-info" href="#" ng-click="abaut()">&nbsp;Ayuda</a></li>
                        <li><a class="fa fa-info" href="#" ng-click="abaut()">&nbsp;Sobre PrestaGes</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="auth/logout"><i class="fa fa-power-off"></i>&nbsp;&nbsp;Salir</a></li>
                    </ul>
                </li>

            </ul>

        </div>

        <!--  </div> -->
    <input type="hidden" value="{{Auth::user()->id}}" id="userauth">
    </nav>
    <div class="modal fade " id="modal_abaut" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="vertical-alignment-helper">
            <div class="modal-dialog vertical-align-center" role="document">
                <div class="modal-content">
                    <div class="modal-header mdl-header_fix">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h5 class="modal-title" id="myModalLabel" style="color: white;">PrestaGes soft</h5>
                    </div>
                    <div class="modal-body mdl-body_fix">
                        <input type="hidden" name="_token" value="{{csrf_token()}}" id="token">
                        <div class="text-center">
                            <hr> <span style="font-size: 26px">Desarrollado por:</span> <hr>
                            <p><img src="https://prestages.es/logo.png" width="400" height="150" alt="PRESTAGES" /></p>
                            <p><span style="font-size: 36px">PRESTAGES 3.1</span></p>
                            <span style="font-size: 14px">Sistema de gestión de punto de venta e integración con tienda online prestashop </span></p>
                            <span style="font-size: 16px">WEB: <a href="https://www.prestages.es">www.prestages.es</a></span> <span style="font-size: 14px">MAIL:     <a href="mailto:soporte@prestages.es?Subject=Info%20desde%20prestages%20TPV">Enviar correo</a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid  page_fix">
<body style="background-color: #c4c4c4">
