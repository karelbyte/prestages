app.controller('invoice_ctrl', ['$scope', '$http', 'restAPI',  'invonew',  function ($scope, $http,  restAPI, invonew) {

   $scope.trams = function (x) {
      if (x == 1) { return  'CONTADO'} else  { return 'CREDITO'}
   };

    $scope.currencydef = {
        name : '',
        iso_code : '',
        sign : ''
    };

    $http({
        url: "/config/getcurrency",
        method: "get"
    }).then(function (response) {
        $scope.currencydef.name = response.data.name;
        $scope.currencydef.iso_code = response.data.iso_code;
        $scope.currencydef.sign  = response.data.sign ;
    });


    $scope.paging = "/tpl/paginator.php";
    $scope.erasertpl = "/tpl/eraser.blade.php";

    // ordernar y filtrar
    $scope.order = {
        field : 'invoice.created_at',
        type : 'desc',
        idfs : 'inumero'
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
    $scope.recordpage = 12;
    $scope.rango = rangoutil;

    $scope.getresult = function getResultPages(page)
    {
        $http({
            url: "/invoices/lists",
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

    $scope.$watch('invonew.id', function(){
        console.log(  invonew.id)
        if (invonew.id == true) {
            $scope.getresult($scope.currentpage);

            invonew.id = false;
            console.log(  invonew.id)
        }
    });

  $scope.refresh = function () {
      $scope.getresult($scope.currentpage);
  };

  $scope.prints =  function (id) {
      $('#pdf').html('<iframe src="/invoices/pdf/' +id+ '" style="width: 100%; height: 100%" frameborder="0" allowtransparency="true"></iframe>');
      $('#modal_print').modal('show');
  };


    $scope.setkill = function(name, enty){
        $scope.killname = name;
        $scope.kill = enty;
    };
    
    $scope.delete = function(){
        if ($scope.kill.descrip == 'ABIERTA'){
            $http({
                url: "/invoices/cancel/" +  $scope.kill.id,
                method: "post"
            }).then(function (response) {
                $scope.getresult($scope.currentpage);
                $('#modal_delete').modal('hide');
                alertas("#msj", response.data, null);
            });
        } else {
            $('#modal_delete').modal('hide');
            alertas("#msj",{msj :'No se puede cancelar esta factura', codigo : 500}, null);
        }

    };

    $scope.edit = function(entity){
        if (entity.descrip == 'ABIERTA'){

        } else {

            alertas("#msj",{msj :'No se puede editar esta factura', codigo : 500}, null);
        }

    };

}]);
