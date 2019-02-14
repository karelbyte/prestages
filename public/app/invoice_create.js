app.controller('invoice_create_ctrl', ['$scope', '$http', 'restAPI', 'invonew', function ($scope, $http,  restAPI, invonew) {

    $scope.codeclient = 'PG/1';

    // ordernar y filtrar
    $scope.ordercli = {
        field : 'name',
        type : 'asc',
        idfs : 'icode'
    };

    $scope.filtercli = {
        name: ''
    };

    $scope.setorder = function(field, idfs) {
        setorders(field, idfs,$scope)
    };

    // paginacion y resultados

    $scope.currentpagecli = 1;
    function setpagecli(page){
        $scope.currentpagecli = page;
        $scope.getresultcli(page)
    }

    $scope.setpagecli = setpagecli;
    $scope.recordpagecli = 8;
    $scope.rangocli = rangoutil;

    $scope.getresultcli = function getResultPages(page)
    {
        $http({
            url: "/clients/lists",
            method: "GET",
            params: {start : page-1, take: $scope.recordpagecli, fillter : $scope.filtercli, order: $scope.ordercli}
        }).then(function (response) {
            $scope.clientes = response.data.data;
            $scope.totalpagecli =  Math.ceil(parseInt(response.data.total)/ $scope.recordpagecli);
        })
    };

    $scope.$watch('recordpagecli + filtercli.name + ordercli.field + ordercli.type', function(){
        $scope.getresultcli($scope.currentpagecli);
    });


    $scope.findclient = function () {
        $scope.getresultcli($scope.currentpagecli);
        $('#find_client').modal('show')
    };

    $scope.paseclient = function (x) {
        console.log(x);
        $scope.fac.idclient = x.id;
        $scope.codeclient = x.code;
        $('#find_client').modal('hide');
    };

    //---------------------------------
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
    $scope.createtpl = "/tpl/invoice_create.php";

    $('#datetimepicker').datetimepicker(
        {
            useCurrent: true,
            format:'YYYY-MM-DD HH:mm:ss'
        }
    );


    $('#femision').val(moment().format('YYYY-MM-DD HH:mm:ss'));

    $scope.client = {
        valor: null,
        availableOptions: []
    };

    $scope.formapago = {
        valor: null,
        availableOptions: [{id :1, fpago: 'CONTADO'}, {id :2, fpago: 'CREDITO'}]
    };



    $scope.fac1= {
        fecha : moment().format('DD.MM.YYYY'),
        numero : '',
        referencia : '',
        idclient : 0,
        formpago : 0,
        fullimport : 0.00,
        tick : []
    };

    $scope.arrays = [];


    $scope.addtick = function (x) {
       if (_.contains( $scope.fac.tick, x.id)) {
          $scope.fac.tick =  _.without($scope.fac.tick,  x.id);
           for(i in  $scope.arrays ){
              if ( $scope.arrays[i].id == x.id) {  $scope.arrays.splice(i, 1)}
            }
          x.check = false;
      } else {
          $scope.fac.tick.push(x.id);
          $scope.arrays.push(x);
          x.check = true;
      }
        $scope.fac.fullimport = 0;
        $scope.fac.referencia = '';
        for(i in  $scope.arrays ){
            $scope.fac.fullimport +=  $scope.arrays[i].fullimport;
            $scope.fac. referencia +=  '--'+$scope.arrays[i].code;
        }

    };

    $scope.fac= {
        fecha : moment().format('DD.MM.YYYY'),
        numero : '',
        referencia : '',
        idclient : null,
        formpago : null,
        fullimport : 0.00,
        tick : []
    };

    function validafac(fac) {
        flag = false;
        if (fac.femision == "") {
            toastr["info"]("Falta la fecha de emisión", "<b>Faltan datos</b>");
            flag = true
        }
        if (fac.idclient == null){
            toastr["info"]("Cual es el cliente a facturar?","<b>Faltan datos</b>");
            flag = true
        }
        if (fac.fullimport == 0){
            toastr["info"]("Elija algun ticket!","<b>Faltan datos</b>");
            flag = true
        }

        return flag;
    }
    $scope.generar = function () {
        $scope.fac.femision = $('#femision').val();
        if (!validafac($scope.fac)) {
            $http({
                url: "/invoices/save",
                method: "post",
                data :  {fac :  $scope.fac}
            }).then(function (response) {
                $('#modal_invoice').modal('hide');
                $('#pdf').html('<iframe src="/invoices/pdf/' +response.data+ '" style="width:100%; height: 100%" frameborder="0" allowtransparency="true"></iframe>');
                $('#modal_print').modal('show');
               //  window.open('/invoices/pdf/'+response.data,'_blank');
                toastr["info"]("Actualice el modulo para ver los cambios!","<b>Atención</b>");
                invonew.id = true;
            });
        }
    };

    // ordernar y filtrar
    $scope.order = {
        field : 'tickets.created_at',
        type : 'desc',
        idfs : 'inumero'
    };
    $scope.filter = {
        name: ''
    };
    $scope.setorder = function(field, idfs) {
        setorders(field, idfs, $scope)
    };

    // paginacion y resultados

    $scope.currentpage = 1;
    function setpage(page){
        $scope.currentpage = page;
        $scope.getresult(page)
    }

    $scope.setpage = setpage;
    $scope.recordpage = 5;
    $scope.rango = rangoutil;

    $scope.getresult = function getResultPages(page)
    {
        $http({
            url: "/invoices/ticket/lists",
            method: "GET",
            params: {start : page-1, take: $scope.recordpage, fillter : $scope.filter, order: $scope.order}
        }).then(function (response) {
            $scope.tickets = response.data.data;
            $scope.totalpage =  Math.ceil(parseInt(response.data.total)/ $scope.recordpage);
            for(i in  $scope.tickets ){
              if(_.contains( $scope.fac.tick, $scope.tickets[i].id)) {  $scope.tickets[i].check = true}
            }
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
        $http({
            url:  "/invoices/surrogate",
            method: "GET"
        }).then(function (response) {
            angular.copy($scope.fac1,  $scope.fac);
            $scope.arrays = [];
            $scope.fac.numero = response.data;
            $scope.getresult($scope.currentpage);
            $('#modal_invoice').modal('show');
        });

    };

}]);

