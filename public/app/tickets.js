app.controller('tickets_ctrl', ['$scope', '$http', 'restAPI', function ($scope, $http, restAPI) {
    $scope.paging = "/tpl/paginator.php";
    $scope.erasertpl = "/tpl/eraser.blade.php";
    $scope.eraser_ticktpl = "/tpl/eraser_ticket.blade.php";
    $scope.createtpl = "/tpl/ticketstpl.php";

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

    // ordernar y filtrar
    $scope.order = {
        field : 'tickets.created_at',
        type : 'desc',
        idfs : 'created'
    };

    $scope.setfilter = function (cd)
    { 
        $scope.filter.code = cd 
    }  
   $scope.filter = {
        name: '',
        code: '',
        idate: '',
        fdate: '',
        status: '',
        hoy: '',
        idclose: ''
    };
    $scope.setorder = function(field, idfs) {
        setorders(field, idfs, $scope)
    };

    
    $('#datetimepicker1').datetimepicker(
        {
            useCurrent: true,
            format: 'DD/MM/YYYY'
        }
    );

    $('#datetimepicker2').datetimepicker(
        {
            useCurrent: true,
            format: 'DD/MM/YYYY'
        }
    );

    $scope.getchange = function () {
     
        $scope.filter.idate =  $scope.idate
        
        $scope.filter.fdate =  $scope.fdate
        
        $scope.getresult($scope.currentpage);

    }

    $scope.idate = moment().format('DD/MM/YYYY');

    $scope.fdate = moment().format('DD/MM/YYYY');

   
    // paginacion y resultados

    $scope.currentpage = 1;
    function setpage(page){
        $scope.currentpage = page;
        $scope.getresult(page)
    }

    $scope.formattedDate =  function formattedDate(inputFormat) {
        function pad(s) { return (s < 10) ? '0' + s : s; }
        var d = new Date(inputFormat);
        return [pad(d.getDate()), pad(d.getMonth()+1), d.getFullYear(),  d.getMinutes() ].join('/'); 
      }

    $scope.setpage = setpage;
    $scope.recordpage = 10;
    $scope.rango = rangoutil;

    $scope.getresult = function getResultPages(page)
    {
        if ($scope.h == 'actual') {urls = "/tickets/lists" } else { urls = "/tickets/listsh"}
        $http({
            url: urls,
            method: "GET",
            params: {start : page-1, take: $scope.recordpage, fillter : $scope.filter, order: $scope.order}
        }).then(function (response) {
            $scope.lista = response.data.data;
            $scope.statuslist = response.data.statuslist;
            $scope.totalpage =  Math.ceil(parseInt(response.data.total)/ $scope.recordpage);
        })
    };

    $scope.$watch('recordpage + filter.name + filter.code + filter.hoy + filter.status + order.field + order.type + filter.created_at + filter.idclose', function(){
        $scope.getresult($scope.currentpage);
    });

    $scope.ticketsd = [];
    $scope.entity = {};
    $scope.details = function(entity, edit) {
        $scope.entity = entity;
        $scope.tickedit = edit;
        $http({
            url: "tickets/showdetais/" + entity.id,
            method: "GET"
        }).then(function (response) {
            $scope.ticketsd = response.data;
            $scope.form_title = 'Detalles del ticket';
            $scope.figureOutTodosToDisplay($scope.currentp);
            $('#modal_ticket_edit').modal('show')
        })
    };

    $scope.print = function (id, mode) {
        $('#bodys').html('<iframe style="" src="/ticket/print/'+  id +'/' + mode + '/0' + '" frameborder="0"></iframe>');
        $('#print_dialog').modal('show');
    };

    $scope.view = function (id, mode) {
       // window.open('/ticket/pdf/'+  id+'/'+mode);
     $('#view').html('<iframe src="/ticket/pdf/'+  id+'/'+mode+'" style="width: 100%; height: 100%" frameborder="0" allowtransparency="true"></iframe>');
      $('#view_dialog').modal('show');
    };


    // miguel modificado
    $scope.buscatickcancel = function (owner) {
        $scope.currentpage = 1;
            $scope.currentpage = 1;
            $scope.filter.status = "";
            $scope.filter.name = '';
            //$scope.$apply();
            $scope.filter.code = owner;


    };

    //  actualizar inventarios
    $scope.listacanlados = [];
    $scope.pase = true;

    function passstatus(array) {
        var c = 0;
        for (var i = 0; i < array.length; i++) {
           c += (array[i].status == 1) ? 1 : 0;
        }
        return c;
    }

    $scope.deletedet =  function (x) {
    if (passstatus($scope.ticketsd) > 1){
       if (!$scope.pase) {
           toastr["info"]("Para que los cambios permanezcan tiene que guardar al final.", "<b>Atención</b>");
       }
       // $scope.ticketsd = _.without( $scope.ticketsd,  x);
        x.status = 0;
        $scope.entity.fullimport -=  x.fullimport;
        $scope.figureOutTodosToDisplay($scope.currentp);
        $scope.listacanlados.push(x);
        $scope.pase = false;}
        else {  toastr["info"]("Para eliminar este ultimo detalle tiene que cancelar el ticket", "<b>Atención</b>");}
    };

    $scope.close = function () {
        $scope.pase = true;
        $scope.getresult($scope.currentpage);
    };

    $scope.updatestock = function (art) {
        $http({
            url: '/products/updatestock/mas',
            method: 'POST',
            params: {sale: art}
        }).then(function successCallback(response) {

        }, function errorCallback(response) {

        });
    };
    article = {
        id : 0,
        stock_id: 0,
        amount: 0,
        ean13 : ''
    };

    $scope.ifv = function (name, st) {
       return (st === 0) ? name + ' (Cancelado)': name;
    };

    $scope.save = function () {
        var arrays = [];
        angular.copy($scope.listacanlados, arrays);
        for (d in arrays) {
            art = {};
            article.id =  arrays[d].id;
            article.amount = arrays[d].cant;
            article.stock_id = arrays[d].stock_id;
            article.ean13 =  arrays[d].ean13;
            angular.copy(article, art);
            $scope.updatestock(art);
            angular.copy({}, article);
        }
        if ($scope.entity.fullimport === 0) { status = 5} else { status = 4}
        $http({
            url: '/tickets/chengestatus/'+   $scope.entity.id + '/' + status,
            method: 'POST',
            data : {import : $scope.entity.fullimport}
        }).then(function successCallback(response) {
            $scope.getresult($scope.currentpage);
            toastr["success"]("Operación realizada con éxito, se actualizaron los inventarios!", "<b>Exelente</b>");
            $('#modal_ticket_edit').modal('hide');
        }, function errorCallback(response) {

        });


    };
    $scope.idkill = 0;
    $scope.pay = ''
    $scope.typede = ''
    $scope.cancel = function(itm){
        $scope.idkill = itm.id;
        $scope.pay = itm.typepayment == 'credit' ? 'Credito' : 'Efectivo';
        $scope.typede = itm.typepayment
        $scope.killname = 'Cancelar ticket';
        $('#modal_delete').modal('show');
    };

    $scope.paychange = function(dx) {
        $scope.typede = dx
    }

       
    $scope.delete = function(op){
        $http({
            url: '/tickets/cancel/'+ $scope.idkill + '/' + 10 + '/' + $scope.typede,  
            method: 'POST'
        }).then(function successCallback(response) {
            $scope.getresult($scope.currentpage);
            toastr["success"]("Operación realizada con éxito, se actualizaron los inventarios!", "<b>Exelente</b>");
            $('#modal_delete').modal('hide');
            // asi trabajoooooooooooooooooooooooooooooooooooooooo
           if (op == 1) { $scope.print(response.data, true) }
        }, function errorCallback(response) {
            $('#modal_delete').modal('hide');
            toastr["info"](response.data, "<b>Atención</b>");
        });
    };

    // paginacion detalles venta
    $scope.listaventa = [];
    $scope.itemsPerPage = 6;
    $scope.currentp = 1;
    $scope.tpage = 0;

    $scope.figureOutTodosToDisplay = function(page) {
        $scope.tpage =  Math.ceil($scope.ticketsd.length / $scope.itemsPerPage);
        var begin = ((page - 1) * $scope.itemsPerPage);
        var end = begin + $scope.itemsPerPage;
        $scope.listaventa =  $scope.ticketsd.slice(begin, end);
    };

    $scope.figureOutTodosToDisplay($scope.currentp);

    $scope.pageChanged = function(xp) {
        $scope.currentp = xp;
        $scope.figureOutTodosToDisplay(xp);
    };

    $scope.paginar = function(totalpage, currentpage){
        var star,  end, total;
        total= (totalpage !== null )? parseInt(totalpage) : 0;
        if (total <= 5)
        {
            star = 1;
            end = total+1;
        } else{
            if ( currentpage <= 2) {
                star = 1;
                end = 6;
            } else if (currentpage + 2 >=  total) {
                star =  total - 5;
                end = total + 1;
            } else {
                star=  currentpage - 2;
                end =  currentpage + 3;
            }

        }
        return  _.range(star, end);
    }

}]);



