app.controller('config_clr', ['$http', '$scope', 'upload', function ($http, $scope, upload) {


   $scope.priceset = {
       modif : 1
   };

    $http({
        url: "/config/getprint",
        method: "get"
    }).then(function (response) {
       $scope.print = response.data;
    });

    $scope.setprint = function () {
     $http({
            url: "/config/setprint",
            method: "post",
            data : $scope.print
        }).then(function (response) {
            toastr["success"]("Datos actualizados correctamente", "<b>Exelente!</b>")
        });
    };


    $http({
        url: "/config/getpricetap",
        method: "get"
    }).then(function (response) {
        $scope.priceset.modif = response.data;
    });

    $scope.setpricetap = function () {
        $http({
            url: "/config/setpricetap/"+ $scope.priceset.modif,
            method: "post"
        }).then(function (response) {
            toastr["success"]("Datos actualizados correctamente", "<b>Exelente!</b>")
        });
    };


    $scope.presta = {
        id : 1,
        url : '',
        keycode : ''
    };

   $scope.getconect = function () {
       $http({
           url: "/config/getprestashop",
           method: "GET"
       }).then
       (function successCallback(response) {
           $scope.presta.url = response.data.url;
           $scope.presta.keycode = response.data.keycode;
       }, function errorCallback(response) {
           toastr["info"]("Actualice la pagina, ha ocurrido un error", "<b>Alerta</b>")
       });
   };


    $scope.getcache = function () {
        $('#modal_cache').modal('show');
        $http({
            url: "/cache",
            method: "GET"
        }).then
        (function successCallback(response) {
            toastr["success"](response.data, "<b>Exelente</b>")
            $('#modal_cache').modal('hide');
        }, function errorCallback(response) {
            $('#modal_cache').modal('hide');
            toastr["info"]("A ocurrido un error!", "<b>Alerta</b>")
        });
    };

    $scope.getconect();


    $scope.addconect = function () {
       if ($scope.presta.url != null && $scope.presta.keycode != null){
            $http({
                url: "/config/setprestashop",
                method: "POST",
                params: {presta :$scope.presta}
            }).then(function (response) {
                toastr["success"](response.data.msj, "<b>Exelente</b>")
            });
        } else toastr["info"]("Faltan datos por completar.", "<b>Faltan datos</b>")
    };

    $scope.testconect = function () {
        if ($scope.presta.url != null && $scope.presta.keycode != null){
            $http({
                url: "/config/testprestashop",
                method: "get"
            }).then(function (response) {
                if (response.data.codigo == 200 ) {
                    toastr["info"](response.data.msj, "<b>Exelente</b>")
                } else  toastr["error"](response.data.msj, "<b>Error</b>")
            });
        } else toastr["info"]("Faltan datos por completar.", "<b>Faltan datos</b>")
    };

    $scope.currency = {};
    $scope.getcurrency = function () {

            $http({
                url: "/config/getcurrencypresta",
                method: "get"
            }).then(function (response) {
                if (response.data.codigo == 200 ) {
                    $scope.currency = response.data.data
                } else  toastr["error"](response.data.msj, "<b>Error</b>")
            });

    };

    $scope.addcurrency = function () {
        if ($scope.currency.name != null && $scope.currency.iso_code != null && currency.sign == null){
            $http({
                url: "/config/setcurrency",
                method: "POST",
                params: {currency : $scope.currency}
            }).then(function (response) {
                toastr["success"](response.data.msj, "<b>Exelente</b>")
            });
        } else toastr["info"]("Faltan datos por completar.", "<b>Faltan datos</b>")
    };

    $http({
        url: "/config/getcurrency",
        method: "get"
    }).then(function (response) {
        $scope.currency = response.data;
    });

    // datos de la compañia
    $scope.company = {};
    function  getcompany() {

        $http({
            url: "/config/getcompany",
            method: "get"
        }).then(function (response) {
            if (!$.isEmptyObject(response.data)) {
            $scope.company = response.data;
            $scope.imgrute = $scope.company.logo
            }
            else {
                $scope.imgrute = 'company.png';
            }
        });

    }

    getcompany();
    expr = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;


    $scope.addcompany = function () {
        if ($scope.company.logo == null) {$scope.company.logo = 'company.png'}
        if (expr.test($scope.company.email)) {
        if ($scope.company.name != null && $scope.company.address != null) {
            $http({
                url: "/config/setcompany",
                method: "POST",
                params: {company : $scope.company}
            }).then(function (response) {
                toastr["success"](response.data.msj, "<b>Exelente</b>")
            });
        } else toastr["info"]("Faltan datos por completar.", "<b>Faltan datos</b>") }
        else toastr["info"]("El correo no es valido.", "<b>Alerta</b>")
    };

  // todo sobre img

    $scope.setimg = function () {
        $('#imgperson').click();
    };

    $('#imgperson').on('change', function () {
        setTimeout(function () {
            $scope.uploadFile();
        }, 2000)
    });

    $scope.uplogo = false;

    $scope.uploadFile = function()
    {
        var name = 'noname'+  Math.trunc(Math.random() * (900 - 1) + 1);
        var file = $scope.file;
        $scope.uplogo = true;
        upload.uploadFile(file, name).then(function(res)
        {
            $scope.imgrute = res.data;
            $scope.company.logo = $scope.imgrute;
            $scope.uplogo = false;
            toastr["info"]("Use 'Guadar', para que los datos persistan.", "<b>Información</b>")

        })
    };

}]);

