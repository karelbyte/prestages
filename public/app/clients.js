app.controller('clients_ctrl', ['$scope', '$http', 'restAPI', function ($scope, $http,  restAPI) {
    $scope.paging = "/tpl/paginator.php";
    $scope.erasertpl = "/tpl/eraser.blade.php";
    $scope.createtpl = "/tpl/clientstpl.php";
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
    $scope.recordpage = 14;
    $scope.rango = rangoutil;

    $scope.getresult = function getResultPages(page)
    {
        $http({
            url: "/clients/lists",
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


    $scope.entity = {};

    $scope.toggle = function(modalstate, id) {
        angular.copy({}, $scope.retorno);
        $scope.modalstate = modalstate;
        switch (modalstate) {
            case 'add':
                if (id == null){
                    $scope.form_title = "Agregar un cliente.";
                    angular.copy({},$scope.entity);

                } else {;
                    restAPI.rest('/clients').get({id: id}).$promise.then(function(response){
                        $scope.form_title = "Clonar datos de una persona.";
                        $scope.entity = response;

                    });
                }
                break;
            case 'edit':
                restAPI.rest('/clients').get({id: id}).$promise.then(function(response){
                    $scope.form_title = "Detalles del cliente.";
                    $scope.entity = response;
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
                restAPI.rest('/clients').update({id: id}, $scope.entity).$promise.then(function successCallback(response) {
                    alertas("#msj", response, "#name");
                    $scope.getresult($scope.currentpage);
                    $('#modal_add_edit').modal('hide')
                }, function errorCallback(msj) {
                    angular.copy({}, $scope.retorno);
                    $scope.retorno = msj.data;

                });

        } else {
            restAPI.rest('/clients').save($scope.entity).$promise.then(function successCallback(response) {
                $scope.retorno = response;
                alertas("#msj", response, "#name");
                $('#modal_add_edit').modal('hide')
                $scope.getresult($scope.currentpage);
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
        restAPI.rest('/clients').delete({id:$scope.kill}).$promise.then(function(response){
            $("#modal_delete").modal('toggle');
            alertas("#msj", response, null);
            $scope.getresult($scope.currentpage);
        });
    };

    $scope.setdef = function (id) {
        $http({
            url: "/clients/setdef/"+id,
            method: "POST"
        }).then(function (response) {
            alertas("#msj", {codigo:200, msj:'Cliente actualizado.'}, null);
            $scope.getresult($scope.currentpage);
        })
    }
}]);
/**
 * Created by root on 31/07/16.
 */
