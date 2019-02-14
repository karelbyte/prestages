app.controller('sales_ctrl', ['$scope', '$http', 'userauth',  function ($scope, $http, userauth) {

    $scope.paging = "/tpl/paginator.php";
    document.getElementById("price").disabled = false;

    $scope.myFunct = function(keyEvent) {
        if (keyEvent.which === 13)
            $scope.scancode();
    };

    $('#scanbox').select();

    $scope.variosclick = function() {
       $scope.sale.codeEan13 = '100';
       $scope.scancode();
    };


    $scope.myFunctC = function(keyEvent) {
        if (keyEvent.which === 13)
            $scope.findproduct();
    };

    $scope.varios = false;
    $scope.priceold = 0;

    $scope.keyamount = function(keyEvent) {
        if (keyEvent.which === 13){
           /* if  (($scope.entity.amount == 0) || ($scope.entity.amount =='') || isNaN($scope.entity.amount ) ) {$scope.entity.amount = 1 }
            $scope.atras = false
            $scope.calprice();
            $scope.priceold = $scope.entity.fullprice;
            if ($scope.sale.codeEan13.toUpperCase() != 'VARIOS' &&  $scope.sale.codeEan13 != '100') { $('#adds').focus()} else { $('#iva').select()} */
           $scope.addsale();
        }
    };

    $scope.keyiva = function(keyEvent) {
        if (keyEvent.which === 13) {
           /* if  (($scope.entity.IVArate == '') || isNaN($scope.entity.IVArate) ) {$scope.entity.IVArate = 0.0 }
            $scope.atras = false
             $scope.calprice();
            $scope.priceold = $scope.entity.fullprice;
            $('#discount').select(); */
            $scope.addsale();
        }
   
    };

    $scope.keyprice= function(keyEvent) {

        if (keyEvent.keyCode === 13) {
          /*  if ($scope.entity.price === '' || isNaN($scope.entity.price))  $scope.entity.price = 0;
            $scope.atras = false;
            $scope.calprice();
            $scope.priceold = $scope.entity.fullprice;
           $('#cantarticulos').select(); */

            $scope.addsale();
        }

    };

    $scope.keydis= function(keyEvent) {
        if (keyEvent.which === 13) {
           /* if  (($scope.entity.discount === '') || isNaN($scope.entity.discount) ) {$scope.entity.discount = 0}
            $scope.atras = false
            $scope.calprice();
            $scope.priceold = $scope.entity.fullprice;
            $('#full').select();*/
            $scope.addsale();
        }
    };

    $scope.setsale = function(keyEvent) {
        if (keyEvent.which === 13) {
            $scope.addsale();
        }
    };

    $scope.calprice = function () {
      
        $scope.imp_precio_por_aticulos = $scope.entity.price_base * $scope.entity.amount;

        $scope.entity.IVAvalue = ($scope.imp_precio_por_aticulos   * $scope.entity.IVArate) / 100;

        $scope.entity.price = (parseFloat( $scope.entity.price_base)  + (parseFloat($scope.entity.price_base)  *  parseFloat($scope.entity.IVArate) /100)).toFixed(2)

        $scope.entity.fullprice = ($scope.entity.price * $scope.entity.amount) -  ((($scope.entity.price * $scope.entity.amount) * $scope.entity.discount) / 100);


    }; 

   /* $scope.atras = false;

    $scope.$watch('entity.amount + entity.IVArate + entity.discount', function () {
      // if (!$scope.atras) $scope.calprice();
        $scope.calprice();
    }, true); */

    $scope.$watch('entity.price', function () {

        $scope.entity.price_base = $scope.entity.price

        $scope.imp_precio_por_aticulos = $scope.entity.price_base * $scope.entity.amount;

        $scope.entity.IVAvalue = ($scope.imp_precio_por_aticulos * $scope.entity.IVArate) / 100;

        $scope.entity.price_base =  $scope.entity.price_base -  $scope.entity.IVAvalue

        $scope.entity.fullprice = ($scope.entity.price * $scope.entity.amount) - ((($scope.entity.price * $scope.entity.amount) * $scope.entity.discount) / 100);

      }, true); 

      $scope.$watch('entity.amount', function () {
        
        $scope.imp_precio_por_aticulos = $scope.entity.price * $scope.entity.amount;

        $scope.entity.IVAvalue = ($scope.imp_precio_por_aticulos  * $scope.entity.IVArate) / 100;

        $scope.entity.fullprice = ($scope.entity.price * $scope.entity.amount) -  ((($scope.entity.price * $scope.entity.amount) * $scope.entity.discount) / 100);

      }, true); 


      $scope.$watch('entity.IVArate', function () {
        

         $scope.imp_precio_por_aticulos = $scope.entity.price * $scope.entity.amount;

         $scope.entity.IVAvalue = ($scope.imp_precio_por_aticulos  * $scope.entity.IVArate) / 100;

         $scope.entity.price_base =  $scope.entity.price -  $scope.entity.IVAvalue

         $scope.entity.fullprice  =  $scope.imp_precio_por_aticulos 

      }, true); 


      
      $scope.$watch('entity.discount', function () {
    
       $scope.imp_precio_por_aticulos = $scope.entity.price * $scope.entity.amount;

        $scope.entity.IVAvalue = ($scope.imp_precio_por_aticulos  * $scope.entity.IVArate) / 100;

       // $scope.entity.price_base =  $scope.entity.price -  $scope.entity.IVAvalue;

        $scope.entity.fullprice =  ($scope.entity.price * $scope.entity.amount) - ((($scope.entity.price * $scope.entity.amount) * $scope.entity.discount) / 100);

       // $scope.entity.fullprice  =  $scope.imp_precio_por_aticulos 
     
        //$scope.entity.fullprice = ($scope.entity.price * $scope.entity.amount) -  ((($scope.entity.price * $scope.entity.amount) * $scope.entity.discount) / 100);

       
     }, true); 


    /*$scope.calpricex = function () {
       
        $scope.imp_precio_por_aticulos = $scope.entity.price_base * $scope.entity.amount;

        $scope.entity.IVAvalue = ($scope.imp_precio_por_aticulos   * $scope.entity.IVArate) / 100;

        $scope.entity.fullprice = ($scope.entity.price * $scope.entity.amount) -  ((($scope.entity.price * $scope.entity.amount) * $scope.entity.discount) / 100);

    };*/

 function validapresventa(sale) {
        flag = false;
        if (sale.ean13 === "") {
            toastr["info"]("No se ha escaneado un producto válido", "<b>Faltan datos</b>");
            flag = true
        }
        if (isNaN(sale.amount)) {
            toastr["info"]("El monto de la cantidad no es válido", "<b>Datos erroneos</b>");
            flag = true
        }
        if (isNaN(sale.discount) ) {
            toastr["info"]("El monto del descuento no es válido", "<b>Datos erroneos</b>");
            flag = true
        }
        if (isNaN(sale.fullprice) ) {
            toastr["info"]("El monto del importe no es válido", "<b>Datos erroneos</b>");
            flag = true
        }
        return flag;
    }

   /* $scope.keyfull= function(keyEvent) {
       if (keyEvent.which === 13) {
          if (!validapresventa($scope.entity)) {
              if  ($scope.priceold !== $scope.entity.fullprice){
                  if  (($scope.entity.price === 0) || ($scope.entity.price === '') || isNaN($scope.entity.price) ) {

                      $scope.atras = true;
                      $scope.entity.price =  (((($scope.entity.fullprice *100) / (100 - $scope.entity.discount)) / (1+ ($scope.entity.IVArate / 100))) * $scope.entity.amount)   //.toFixed(2);
                      $scope.calprice();

                  } else {

                      $scope.atras = true;
                     // $scope.entity.discount = (100 - ( ($scope.entity.fullprice * 100) / ( ($scope.entity.price * $scope.entity.amount) * (1+ ($scope.entity.IVArate / 100))))).toFixed(2);
                      $scope.entity.discount = (100 - ( ($scope.entity.fullprice * 100) / ( ($scope.entity.price * $scope.entity.amount) ))).toFixed(2);
                      $scope.calprice();
                  }

                if ($scope.sale.codeEan13.toUpperCase() !== 'VARIOS' &&  $scope.sale.codeEan13 !== '100') {$scope.addsale()} else $('#descrip').select();
            } else  if ($scope.sale.codeEan13.toUpperCase() !== 'VARIOS' &&  $scope.sale.codeEan13 !== '100') {$scope.addsale()} else $('#descrip').select();
         }
       }
    };*/

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

    $scope.tap = 0;

    $http({
        url: "/config/getpricetap",
        method: "get"
    }).then(function (response) {
        $scope.tap = response.data;
    });

    $scope.codes = {
        valor: null,
        availableOptions: []
    };

    $http({
        url: "/users/codes",
        method: "get"
    }).then(function (response) {
        $scope.codes.availableOptions = response.data;
        $scope.codes.valor = userauth.id;
    });

    $scope.findtpl = "/tpl/findproducttpl.php";
    $scope.detailtpl = "/tpl/productdetailtpl.php";

    $('#datetimepicker1').datetimepicker(
        {
            useCurrent: true,
            format: 'DD.MM.YYYY'
        }
    );
    var f = moment().format('DD.MM.YYYY');

    $scope.sale = {
        codeclient: '',
        codeEan13: '',
        name: '',
        date: f
    };


    function findclients(){
        $http({
            url: "/clients/getdef",
            method: "get",
            params: {code: $scope.sale.codeclient}
        }).then(function (response) {
            if (response.data.codigo === 200) {
                $scope.client = response.data.client;
                $scope.sale.name = $scope.client.name;
                $scope.sale.codeclient = $scope.client.code;
            } else {
                {
                    if (response.data.codigo !== 300) {
                     $('#find_client').modal('show');} else {
                   toastr["error"](response.data.msj, "Error") }
                }
            }
        })
    }

    findclients();

    $scope.findclient = function () {
      findclients();
    };

    $scope.paseclient = function (x) {
        $scope.client = x;
        $scope.sale.name = $scope.client.name;
        $scope.sale.codeclient = $scope.client.code;
        $('#find_client').modal('hide');
    };

    $scope.entity = {
        id: 0,
        name: '',
        description: '',
        ean13: '',
        category: '',
        price: 0,
        price_base : 0,
        amount: 0,
        IVArate: 0,
        IVAvalue: 0,
        discount: 0,
        fullprice: 0,
        stock_id : 0
    };
    $scope.entityaux = {
        id: 0,
        name: '',
        description: '',
        ean13: '',
        category: '',
        price: 0,
        price_base : 0,
        amount: 0,
        IVArate: 0,
        IVAvalue: 0,
        discount: 0,
        fullprice: 0,
        stock_id : 0
    };

    $scope.scancode = function () {
        if ($scope.sale.codeEan13 !== '') {
          if  ( $scope.sale.codeEan13.toUpperCase() !== 'VARIOS' &&  $scope.sale.codeEan13 !== '100') {
            $scope.eantime = true;
            $http({
                method: 'GET',
                url: '/products/getproduct/' + $scope.sale.codeEan13
            }).then(function successCallback(response) {
               // console.log(response);
                if (response.data.codigo !== 500) {
                    if (response.data.result == null) toastr["info"](response.data.msj, "Alerta");
                    angular.copy($scope.entityaux, $scope.entity);
                    $scope.entity.id = response.data.result.id;
                    $scope.entity.type =  (response.data.result.comb_id === null) ? 'product' : 'combination';
                    $scope.entity.attribute_id = response.data.result.attribute_id;
                    $scope.entity.name = response.data.result.name;
                    $scope.entity.description = response.data.result.description;
                    $scope.entity.ean13 = response.data.result.ean13;
                    $scope.entity.category = response.data.result.category;
                    $scope.entity.price = response.data.result.price;
                    $scope.entity.price_base = $scope.entity.price;
                    $scope.entity.amount = response.data.result.amount;
                    $scope.entity.stock = (response.data.codigo === 201) ? response.data.result.stock.in_stock:  response.data.result.in_stock;
                    $scope.entity.stock_id = (response.data.codigo === 201) ? response.data.result.stock.stock_id :  response.data.result.stock_id;
                    $scope.entity.IVArate = (response.data.codigo === 201) ? response.data.result.tax.rate  : response.data.result.tax;
                    $scope.entity.IVAvalue = 0;
                    $scope.entity.discount = 0;
                    $scope.entity.fullprice = 0;
                    $scope.eantime = false;
                    $('#cantarticulos').select();
                    $scope.calprice();
                    $scope.varios = false;
                    if ($scope.tap === 1) {$('#price').select()} else  {
                        $('#price').select();
                        // document.getElementById("price").disabled = true;
                    }
                    $scope.eantime = false;
                } else {
                    toastr["info"](response.data.msj, "Alerta");
                    $scope.eantime = false;
                }
            }, function errorCallback(response) {
                toastr["error"]("A ocurrido un error, revise la conexión a su tienda prestashop", "Error");
                $scope.eantime = false;
            });
          } else
          {  angular.copy($scope.entityaux, $scope.entity);
              $scope.entity.id = -1;
              $scope.entity.type = 'product';
              $scope.entity.attribute_id = -1;
              $scope.entity.name = 'VARIOS';
              $scope.entity.description = '';
              $scope.entity.ean13 = '100';
              $scope.entity.category = '';
              $scope.entity.price = 0;
              $scope.entity.amount = 1;
              $scope.entity.stock = 1;
              $scope.entity.stock_id = 0;
              $scope.entity.IVArate = 21;
              $scope.entity.IVAvalue = 0;
              $scope.entity.discount = 0;
              $scope.entity.fullprice = 0;
              $scope.priceold = $scope.entity.fullprice;
              $scope.varios = true;
               if ($scope.tap === 1) {$('#price').select()} else  {
                   $('#price').select();
                  // document.getElementById("price").disabled = true;
               }
          }
        } else {
            toastr["info"]("Introduce un codigo de barras.", "<b>Faltan datos</b>")
        }
    };

    $scope.cantarticles = 0;
    $scope.recivido = 0;
    $scope.saldo = 0;

    $scope.totales = function () {
        totalArticle = 0;
        totalIVA = 0;
        totalPrice = 0;
        for (x in $scope.salesarray) {
            totalArticle += Number($scope.salesarray[x].amount);
            totalIVA += Number($scope.salesarray[x].IVAvalue);
            totalPrice += Number($scope.salesarray[x].fullprice);
        }
        $scope.cantArticles = totalArticle;
        $scope.totalIVA = totalIVA;
        $scope.totalPrice = totalPrice;
    };

    $scope.salesarray = [];

    $scope.$watch('salesarray', function () {
        $scope.totales();
    }, true);


    function validaventa(sale) {

        flag = false;

        if (sale.ean13 === "") {
            toastr["info"]("No se ha escaneado un producto válido", "<b>Faltan datos</b>");
            flag = true
        }

        if (isNaN(sale.price) || sale.price <= 0) {
            toastr["info"]("El monto del precio no es válido", "<b>Datos erroneos</b>");
            flag = true
        }

        if (isNaN(sale.amount) ) {
            toastr["info"]("El monto de la cantidad no es válido", "<b>Datos erroneos</b>");
            flag = true
        }

        if (isNaN(sale.discount) || sale.price < 0) {
            toastr["info"]("El monto del descuento no es válido", "<b>Datos erroneos</b>");
            flag = true
        }

        if (isNaN(sale.fullprice)) {
            toastr["info"]("El monto del importe no es válido", "<b>Datos erroneos</b>");
            flag = true
        }

        return flag;
    }


    function iguales(x, y, i)
    {

      if ( (x.ean13 === y.ean13) && (x.name === y.name) && (x.discount === y.discount) && (x.price === y.price)) {
          return {pass : true, index : i}
      }
       return {pass :false, index : i}
    }

    $scope.ivaprices = function(price, rate, amount){

      return ((price * amount) * rate) / 100;
    };

    $scope.fullprices = function(price, rate){
        if  (rate === 0) { return price}
        return (price  * rate) / 100;
    };

    $scope.addsale = function () {
        entidad = {};
        $scope.entity.iduser = userauth.id;
        angular.copy($scope.entity, entidad);
        if (!validaventa(entidad)) {
            add = false;
            index = 0;
           // $scope.entity.price =     $scope.entity.price *    $scope.entity.amount  
           /* if( $scope.salesarray.length > 0) {

                for (var i in  $scope.salesarray) {
                
                    if (!add ) {
                         add = iguales(entidad, $scope.salesarray[i], i).pass;
                          index = iguales(entidad, $scope.salesarray[i], i).index
                     }
                }

                if (add) {
                    $scope.salesarray[index].amount =  Number($scope.salesarray[index].amount) + Number(entidad.amount);
                    $scope.salesarray[index].IVAvalue =  $scope.ivaprices($scope.salesarray[index].price, $scope.salesarray[index].IVArate,  $scope.salesarray[index].amount);
                    $scope.salesarray[index].fullprice =  $scope.fullprices(($scope.salesarray[index].price*  $scope.salesarray[index].amount) + $scope.salesarray[index].IVArate,  $scope.salesarray[index].discount);
               
                } else $scope.salesarray.push(entidad)

            }  else  $scope.salesarray.push(entidad);*/
            $scope.salesarray.push(entidad);
            angular.copy($scope.entityaux, $scope.entity);
            $scope.sale.codeEan13 = "";
            $scope.totales();
            atras = false;
            $('#scanbox').focus();
            $scope.figureOutTodosToDisplay($scope.currentp);

        }
    };

    $scope.delete = function (x) {
        $scope.salesarray = _.without($scope.salesarray, x);
        $scope.figureOutTodosToDisplay($scope.currentp);
    };

    $scope.updatestock = function (art) {
        $http({
            url: '/products/updatestock/menos',
            method: 'POST',
            data: {sale: art}
        }).then(function successCallback(response) {

        }, function errorCallback(response) {

        });
    };

    $scope.calmax5 = function (x) {
        var r = Math.ceil(x);
        if (r % 5 === 0) {
            return r
        } else {
            for (var i = 1; i <= 10; i++) {
                r = r + 1;
                if (r % 5 === 0) { return r }
            }
        }
    };

    $scope.salesclose = function () {
        pass = true;
        $scope.recivido = 0;
        if ($scope.salesarray.length == 0) {
            toastr["info"]("Añade un producto para venderlo.", "<b>Faltan datos</b>");
            pass = false
        }
        if (($scope.sale.codeclient == "")) {
            toastr["info"]("Falta por definir un cliente", "<b>Faltan datos</b>");
            pass = false;
        }
        $scope.recivido = $scope.calmax5($scope.totalPrice);
        if (pass) {
            $('#recived').focus();
            $('#modal_sale').modal('show');

        }

    };

    ticket = {
      codeclient : '',
      fullimport : 0,
      received : 0,
      change : 0,
      status : 1,
      typepayment : 0,
      gift: 0
    };

    $scope.$watch('recivido', function () {
        $scope.saldo =    $scope.recivido - $scope.totalPrice;
    });

    $scope.execsale = function (typeandgift, print) {
       var idticket = 0;
       if ( ($scope.recivido < (Math.round($scope.totalPrice * 100) / 100))  && typeandgift.typepaiment === 1 ){
           toastr["error"]("Estas combrando menos que el importe total", "<b>Alerta</b>")
       } else {
           article = {
               id: 0,
               attribute_id: 0,
               stock_id: 0,
               amount: 0,
               ean13 : ''
           };
           // actualiza stock en prestashop
           var arrays = [];
           angular.copy($scope.salesarray, arrays);
           for (d in arrays) {
               art = {};
               article.id = arrays[d].id;
               article.attribute_id = arrays[d].attribute_id;
               article.amount = arrays[d].amount;
               article.stock_id = arrays[d].stock_id;
               article.ean13 = arrays[d].ean13;
               angular.copy(article, art);
               $scope.updatestock(art);
               angular.copy({}, article);
           }

           ticket.codeclient = $scope.sale.codeclient;
           ticket.fullimport = $scope.totalPrice.toFixed(2);
           ticket.received = $scope.recivido;
           ticket.change = $scope.saldo.toFixed(2);
           ticket.typepayment = typeandgift.typepaiment;
           ticket.gift = typeandgift.tgift;
         $http({
               url: "/sales/setsales",
               method: "POST",
               params: {sale : ticket}
           }).then(function successCallback(response) {
            $scope.idticket = response.data.idticket;
            // for (x in arrays) {

                 $http({
                     url: "/sales/setsalesdetails/" + $scope.idticket,
                     method: "POST",
                     data: {array : arrays}
                 }).then(function (response) {
                     $scope.salesarray = [];
                     $scope.cantArticles = 0;
                     $scope.totalIVA = 0;
                     $scope.totalPrice = 0;
                     $scope.listaventa = [];
                     toastr["success"]("Venta realizada con exito!!, agradece al cliente por su preferencia.", "<b>Exelente</b>");
                     $('#modal_sale').modal('hide');
                      if (print) {
                      $('#bodys').html('<iframe style="" src="/ticket/print/'+  $scope.idticket+'/standard/' + typeandgift.tgift + '" frameborder="0"></iframe>');
                      $('#print_dialog').modal('show');
                      }
                 });

            // }
           }, function errorCallback(response) {
         });



    }};



  //  $scope.titles = 'dfsgf';

    $scope.salescancel = function () {
        $scope.salesarray = [];
        $scope.listaventa = [];
        $scope.figureOutTodosToDisplay($scope.currentp);
        $scope.cantArticles = totalArticle;
        $scope.totalIVA = totalIVA ;
        $scope.totalPrice =  totalPrice;
        $('#scanbox').focus();
    };

    $scope.detail = {
        image : ''
    };

    $scope.productsdetails = function (id, type) {
       if ($scope.entity.ean13 !== 'VARIOS' && $scope.entity.ean13 !== '100' ) {
       if (id != '') {
            $http({
                method: 'GET',
                url: '/products/getproductdetails/' + id + '/'+ type
            }).then(function successCallback(response) {
               $scope.detail = response.data.result;
                $('#modal_product_detail').modal('show');
            }, function errorCallback(response) {
                // por implemetar
            });

        } else {
            toastr["info"]("Busca un producto primeramente.", "<b>Faltan datos</b>")
        } }
        else { toastr["info"]("No existen detalles para varios.", "<b>Atención</b>")}
    };

    $scope.buscar = function () {
        $scope.form_title = 'Buscar producto';
        $('#modal_find').modal('show');
    };

    $scope.criterio = '';
    $scope.rotate = false;
    $scope.header = false;
    $scope.search = [];

   

    $scope.selected_options = [
        {id: 1, name: 'Nombre'},
        {id: 2, name: 'Referencia'}
    ]

    $scope.findfor =  $scope.selected_options[0];
    
    $scope.findproduct = function () {
       $scope.combinationfinds = [];
       $scope.listabuscar = [];
       $scope.search = [];
       $scope.header = false;
        if ($scope.criterio !== ''){
            $scope.rotate = true;
            $http({
                method: 'GET',
                url: '/products/find/' + $scope.criterio + '/' +$scope.findfor.id 
            }).then(function successCallback(response) {
              if (response.data.status !== 'fail'){
                $scope.search = response.data.result
                $scope.figureOutTodosToDisplayFind($scope.currentfind);
                $scope.rotate = false;
                $scope.header = true;
              } else
              { toastr["info"]( response.data.message, "<b>Atención</b>");
                  $scope.rotate = false;
                  $scope.header = false;
              }
            }, function errorCallback(response) {
                toastr["error"]( response.data.message, "<b>Error</b>");
                $scope.rotate = false;
                $scope.header = false;
            });} else
        {
            toastr["info"]("No hay termino a buscar.", "<b>Faltan datos</b>")
        }
    };

   

    $scope.findproduct_forid = function (id) {
        
        $scope.rotate = true;
        $http({
            method: 'GET',
            url: '/products/getproductselect/' + id
        }).then(function successCallback(response) {
         
            $scope.pase_fix(response.data);   
            $scope.rotate = false; 
            $('#modal_find').modal('hide');
       
        }, function errorCallback(response) {
            toastr["error"]( response.data.message, "<b>Error</b>");
            $scope.rotate = false;
          
        });
};

    $scope.combi_select = function (id) {
        
             $scope.rotate = true;
             $http({
                 method: 'GET',
                 url: '/products/getcombinationselect/' + id
             }).then(function successCallback(response) {
                
                 $scope.pase_fix(response.data);   
                 $scope.rotate = false; 
                 $('#modal_find').modal('hide');
            
             }, function errorCallback(response) {
                 toastr["error"]( response.data.message, "<b>Error</b>");
                 $scope.rotate = false;
               
             });
     };
       $scope.stockproduct = 0;

       $scope.product_stock = function (id) {
        
            $scope.rotate = true;
            $http({
                method: 'GET',
                url: '/products/getstock/' + id + '/' + 0
            }).then(function successCallback(response) {
           /* $scope.combinationfinds.forEach(function (it) {
                if (it.idcomb == id) {it.stock = response.data }
            }) */
              toastr["info"]('Existencias del producto: '+ response.data, "<b>Atención</b>"); 
            $scope.rotate = false;            
            }, function errorCallback(response) {
                toastr["error"]( response.data.message, "<b>Error</b>");
                $scope.rotate = false;
            
            });
        };

       $scope.combi_stock = function (id) {
        
             $scope.rotate = true;
             $http({
                 method: 'GET',
                 url: '/products/getstock/' + $scope.myidpro + '/' + id
             }).then(function successCallback(response) {
                $scope.combinationfinds.forEach(function (it) {
                    if (it.idcomb == id) {it.stock = response.data }
                })
               //  toastr["info"]('Existencias del producto: '+ response.data, "<b>Atención</b>"); 
                $scope.rotate = false;            
             }, function errorCallback(response) {
                 toastr["error"]( response.data.message, "<b>Error</b>");
                 $scope.rotate = false;
               
             });
     };

     $scope.pase_fix = function (x) {
        $scope.entity.id = x.id;
        $scope.entity.type =  x.comb_id == null ?  'product' : 'combination' ; //x.type;
        $scope.entity.attribute_id = x.attribute_id;
        $scope.entity.name = x.name;
        $scope.entity.description = x.description;
        $scope.entity.ean13 = x.ean13 == 0 ? '-': x.ean13;
        $scope.entity.category = x.category;
        $scope.entity.price = x.price;
        $scope.entity.price_base = x.price
        $scope.entity.amount = x.amount;
        $scope.entity.stock = x.stock.in_stock;
        $scope.entity.stock_id = x.stock.stock_id;
        $scope.entity.IVArate = x.tax.rate;
        $scope.entity.IVAvalue = 0;
        $scope.entity.discount = 0;
        $scope.entity.fullprice = 0;
        $scope.sale.codeEan13 = x.ean13;
        $scope.calprice();
    };

   $scope.pase = function (x) {
       $scope.entity.id = x.id;
       $scope.entity.type =  x.comb_id == null ?  'product' : 'combination' ; //x.type;
       $scope.entity.attribute_id = x.attribute_id;
       $scope.entity.name = x.name;
       $scope.entity.description = x.description;
       $scope.entity.ean13 = x.ean13 == 0 ? '-': x.ean13;
       $scope.entity.category = x.category;
       $scope.entity.price = x.price ;
       $scope.entity.amount = x.amount;
       $scope.entity.stock = x.in_stock;//x.stock.in_stock;
       $scope.entity.stock_id = x.stock_id; //x.stock.stock_id;
       $scope.entity.IVArate = x.tax; // x.tax.rate;
       $scope.entity.IVAvalue = 0;
       $scope.entity.discount = 0;
       $scope.entity.fullprice = 0;
       $scope.sale.codeEan13 = x.ean13;
       $scope.calprice();
       $('#modal_find').modal('hide');
       $scope.scancode();
   };

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
    $scope.recordpage = 9;
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

  // paginacion detalles venta
    $scope.listaventa = [];
    $scope.itemsPerPage = 9;
    $scope.currentp = 1;
    $scope.tpage = 0;

    $scope.figureOutTodosToDisplay = function(page) {
        $scope.tpage =  Math.ceil($scope.salesarray.length / $scope.itemsPerPage);
        var begin = ((page - 1) * $scope.itemsPerPage);
        var end = begin + $scope.itemsPerPage;
        $scope.listaventa = $scope.salesarray.slice(begin, end);
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
    };


   // paginacion de resultados del busqueda
    $scope.listabuscar = [];
    $scope.itemsPerPage = 9;
    $scope.currentfind = 1;
    $scope.tpagefind = 0;

    $scope.figureOutTodosToDisplayFind = function(page) {
        $scope.tpagefind =  Math.ceil($scope.search.length / $scope.itemsPerPage);
        var begin = ((page - 1) * $scope.itemsPerPage);
        var end = begin + $scope.itemsPerPage;
        $scope.listabuscar = $scope.search.slice(begin, end);
    };

    $scope.figureOutTodosToDisplayFind($scope.currentfind);

    $scope.pageChangeds = function(xp) {
        $scope.currentfind = xp;
        $scope.figureOutTodosToDisplayFind(xp);
    };

    $scope.paginarfind = function(totalpage, currentpage){
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

    $scope.combinationfinds = [];

    $scope.option = [];

    $scope.myreference = ''

    $scope.myname = ''

    $scope.myidpro = ''
    
    $scope.findcombinationfor = function findcombinationfor(x)  {
        $scope.combinationfinds = [];
        $scope.myidpro = x.id
        $scope.myreference = x.reference
        $scope.myname = x.name
        $scope.rotate = true;
        $http({
            method: 'GET',
            //url: '/products/findcombination/' + x.id + '/' + x.id_default_combination
            url: '/products/getcombinationfind/'+ x.id
        }).then(function successCallback(response) {
          if (response.data.status !== 'fail'){
            $scope.combinationfinds = response.data.comb
            $scope.option = response.data.option
            console.log(response.data.comb)
            $scope.rotate = false;
          } else
          { toastr["info"]( response.data.message, "<b>Atención</b>");
              $scope.rotate = false;
              $scope.header = false;
          }
        }, function errorCallback(response) {
            toastr["error"]( response.data.message, "<b>Error</b>");
            $scope.rotate = false;
            $scope.header = false;
        });


    }

    $scope.getname = function getname(com) {
      var name = ''  
      com.option.forEach(function (com) {
        $scope.option.forEach(function (op) {
          if (com == op.id)  name += op.name 
        })  
      })  
      return name
    }
  // find combination 
 $scope.findcombination = function findcombination(x)
    {
         $http({
                method: 'GET',
                //url: '/products/findcombination/' + x.id + '/' + x.id_default_combination
                url: '/products/findcombination/'+ x.id
            }).then(function successCallback(response) {
              if (response.data.status !== 'fail'){
               // $scope.search = response.data.result
                console.log(response.data.result)
               /* $scope.figureOutTodosToDisplayFind($scope.currentfind);
                $scope.rotate = false;
                $scope.header = true; */
              } else
              { toastr["info"]( response.data.message, "<b>Atención</b>");
                  $scope.rotate = false;
                  $scope.header = false;
              }
            }, function errorCallback(response) {
                toastr["error"]( response.data.message, "<b>Error</b>");
                $scope.rotate = false;
                $scope.header = false;
            });

    };



}]);
/**
 * Created by root on 31/07/16.
 */

