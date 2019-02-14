app.controller('users_ctrl', ['$scope', '$http', 'restAPI', function ($scope, $http,  restAPI) {
 $scope.paging = "/tpl/paginator.php";
 $scope.erasertpl = "/tpl/eraser.blade.php";
 $scope.createtpl = "/tpl/userstpl.php";
    // ordernar y filtrar
    $scope.order = {
        field : 'name',
        type : 'asc',
        idfs : 'icode'
    };
    $scope.filter = {
        name: ''
    };
    $scope.setorder = function(field, idfs) {
        setorders(field, idfs,$scope)
    };

    // paginacion y resultados

    $scope.currentpage = 1;
    function setpage(page){
        $scope.currentpage = page;
        $scope.getresult(page)
    }

    $scope.setpage = setpage;
    $scope.recordpage = 10;
    $scope.rango = rangoutil;

    $scope.getresult = function getResultPages(page)
    {
        $http({
            url: "/users/lists",
            method: "GET",
            params: {start : page-1, take: $scope.recordpage, fillter : $scope.filter, order: $scope.order}
        }).then(function (response) {
            $scope.lista = response.data.data;
            $scope.totalpage =  Math.ceil(parseInt(response.data.total)/ $scope.recordpage);
        })
    };
    $scope.$watch('recordpage + filter.name + order.field + order.type', function(){
        $scope.getresult($scope.currentpage);
    });

// guardar elimiar y editar


    $scope.entity = {
        id : 0,
        name: '',
        nick : '',
        code : '',
        email : '',
        password : "",
        password_confirmation : "",
        active : false
    };

    $scope.pass = true;

    $scope.setpass = function (ss) {
        $scope.pass = ss ? false : true;
        angular.copy({}, $scope.retorno);
        $scope.entity.password = "";
        $scope.entity.password_confirmation = "";
    };
//---------------------

    $scope.toggle = function(modalstate, id) {
        angular.copy({}, $scope.retorno);
        $scope.modalstate = modalstate;
        switch (modalstate) {
            case 'add':
                if (id == null){
                    $scope.setpass(false);
                    $scope.form_title = "Agregar un usuario.";
                    angular.copy({},$scope.entity);
                    
                } else {
                    $scope.setpass(false);
                    restAPI.rest('/users').get({id: id}).$promise.then(function(response){
                        $scope.form_title = "Clonar datos de una persona.";
                        $scope.entity = response;
                       
                    });
                }
                break;
            case 'edit':
                $scope.setpass(true);
                restAPI.rest('/users').get({id: id}).$promise.then(function(response){
                    $scope.form_title = "Detalles de la usuario.";
                    $scope.entity = response;
                        $scope.entity.active = response.active == 1;
                });

                break;
            default:
                break;
        }
        $('#modal_add_edit').modal('show');
    };
    $scope.retorno = {};
    $scope.save = function(modalstate, id) {
        $scope.confir = false;
        angular.copy({}, $scope.retorno);
        $scope.entity.active =   $scope.entity.active ? 1 : 0;
        if (modalstate === 'edit'){
            if ($scope.pass){
                if ($scope.entity.password.length <6) {$scope.retorno.password = ["Tamaño de contraseña no valido"];    $scope.confir = true}
                if ($scope.entity.password == "" || $scope.entity.password == null) {$scope.retorno.password = ["No se introdujo una contraseña"];    $scope.confir = true}
                if ($scope.entity.password_confirmation == "" ||  $scope.entity.password_confirmation == null) {$scope.retorno.password_confirmation = ["No se confirmo la contraseña"] ;  $scope.confir = true}
                if ($scope.entity.password !=  $scope.entity.password_confirmation) {$scope.retorno.password_confirmation = ["Las contraseñas no coinciden"]; $scope.confir = true }
            } else {$scope.entity.password = ""}
            if (!$scope.confir){
                restAPI.rest('/users').update({id: id}, $scope.entity).$promise.then(function successCallback(response) {
                    alertas("#msj", response, "#name");
                    $scope.getresult($scope.currentpage);
                    $('#modal_add_edit').modal('hide')
                }, function errorCallback(msj) {
                    angular.copy({}, $scope.retorno);
                    $scope.retorno = msj.data;

                });
            }
        } else {
            restAPI.rest('/users').save($scope.entity).$promise.then(function successCallback(response) {
                $scope.retorno = response;
                alertas("#msj", response, "#name");
                $scope.getresult($scope.currentpage);
                $('#modal_add_edit').modal('hide')
            }, function errorCallback(msj) {
                $scope.retorno = msj.data;

            });
        }
        $scope.entity.active =  $scope.entity.active == 1;

    };

    $scope.setkill = function(name, id){
        $scope.killname = name;
        $scope.kill = id;
    };
    $scope.delete = function(){
        restAPI.rest('/users').delete({id:$scope.kill}).$promise.then(function(response){
            $("#modal_delete").modal('toggle');
            alertas("#msj", response, null);
            $scope.getresult($scope.currentpage);
        });
    };

}]);
