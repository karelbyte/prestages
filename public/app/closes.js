app.controller('closes_ctrl', ['$scope', '$http', 'userauth', function ($scope, $http, userauth) {
    $scope.paging = "/tpl/paginator.php";

    $scope.killid = 0;
    $scope.kill = function (id) {
        $scope.killid = id;
        $scope.killname = 'Cencelar cierre de caja.';
        $('#modal_delete').modal('show');
    };

    $scope.delete = function () {
        $http({
            url: "/closes/cancel/" +  $scope.killid,
            method: "post"
        }).then(function (response) {
            $scope.getresult($scope.currentpage);
            $('#modal_delete').modal('hide');
            alertas("#msj", response.data, null);
        });
    };


    $http({
        url: "/closes/lastclose",
        method: "get"
    }).then(function (response) {
        $scope.actual = response.data[0].actual;
        $('#finicio').val(response.data[1].ini);
        $scope.ini = response.data[1].ini;
    });

    $scope.view = function (id, mode) {
        $('#view').html('<iframe  src="/closes/view/'+id+'" style="width: 100%; height: 100%" frameborder="0" allowtransparency="true"></iframe>');
        $('#print_view').modal('show');
    };

    $('#ffinal').val(moment().format('YYYY-MM-DD HH:mm:ss'));

    $scope.saldonew = '0.00';
    $scope.closedetails = {
     iduser : 0,
     creado :  moment().format('YYYY-MM-DD HH:MM:SS'),
     finicio : '',
     ffinal : '',
     inicio_caja : 0,
     money_caja :0,
     efectivo : 0,
     tarjeta : 0,
     cancel_credit: 0,
     cancel_cash: 0,
     cancel_money: 0,
     cantticket :0,
     caja_real: 0
    };

    $scope.calcular_closes = function () {
       $http({
            url: "/closes/getclose",
            method: "post",
            data: {finicio : $('#finicio').val(), ffinal: $('#ffinal').val()}
        }).then(function (response) {
           $scope.closedetails.money_caja = response.data.money_caja;
           $scope.closedetails.cancel_money = response.data.cancel_money;
           $scope.closedetails.efectivo = response.data.cash;
           $scope.closedetails.tarjeta = response.data.credito;
           $scope.closedetails.cantticket = response.data.cant_ticket;
           $scope.closedetails.cancel_credit = response.data.cancel_credit;
           $scope.closedetails.cancel_cash = response.data.cancel_cash;
           $scope.closedetails.caja_real = response.data.caja_real;
           $scope.closedetails.caja_total = response.data.caja_total;
        });
    };

    $scope.print = function (id) {
        $('#bodys').html('<iframe style="" src="/closes/print/'+id+'" frameborder="0"></iframe>');
        $('#print_dialog').modal('show');
    };

    $scope.generarcierre = function () {

        $scope.closedetails.iduser = userauth.id;
        $scope.closedetails.creado =  moment().format('YYYY-MM-DD HH:mm:ss');
        $scope.closedetails.finicio = $('#finicio').val();
        $scope.closedetails.ffinal = $('#ffinal').val();
        $scope.closedetails.inicio_caja =  $scope.saldo;

        $http({
            url: "/closes/setclose",
            method: "post",
            data: {cierre :  $scope.closedetails}
        }).then(function (response) {
            $('#modal_closes').modal('hide');
            $('#bodys').html('<iframe style="" src="/closes/print/'+  response.data.idclose+'" frameborder="0"></iframe>');
            $('#print_dialog').modal('show');
            $scope.getresult($scope.currentpage);
        });
        $http({
            url: "/closes/openbox",
            method: "POST",
            params: {saldo : $scope.saldonew}
        }).then(function (response) {
        })


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


    $scope.boxopen = function () {
        $http({
            url: "/closes/geymonybox",
            method: "get"
        }).then(function (response) {
            $scope.saldo = response.data.money;
            $('#modal_caja').modal('show');
        });

    };

    $scope.closebox = function () {
        if ($scope.ini != null) {
        $http({
            url: "/closes/geymonybox",
            method: "get"
        }).then(function (response) {
            $scope.saldo = response.data.money;
            $('#modal_closes').modal('show');
        })}
        else {
            toastr["info"]("No hay tickets para generar un cierre de caja!!", "<b>Atención!</b>")
        }
    };

    $scope.preview = function () {
        $('#view').html('<iframe src="/closes/pdf" style="width: 100%; height: 100%" frameborder="0" allowtransparency="true"></iframe>');
        $('#print_view').modal('show');
    };


    $scope.savebox = function () {
        $http({
            url: "/closes/openbox",
            method: "POST",
            params: {saldo : $scope.saldo}
        }).then(function (response) {
            toastr["info"]("Saldo guardado", "<b>Exelente!</b>")
        })
    };


    $('#datetimepicker1').datetimepicker(
        {
           useCurrent: true,
           format:'YYYY-MM-DD HH:mm:ss'
        }
    );
    $('#datetimepicker2').datetimepicker(
        {
            useCurrent: true,
            format: 'YYYY-MM-DD HH:mm:ss'
        }
    );

    // ordernar y filtrar
    $scope.order = {
        field : 'closes.creado',
        type : 'desc',
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
            url: "/closes/lists",
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
}]);