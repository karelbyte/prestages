var app = angular.module('presujet', ['ngResource', 'ngSanitize'])

.config(function($interpolateProvider, $httpProvider)
{
  $httpProvider.defaults.useXDomain = true;
  $interpolateProvider.startSymbol('<%').endSymbol('%>');

    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": false,
        "positionClass": "toast-bottom-right",
        "preventDuplicates": false,
        "showDuration": "200",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };


})
.factory('userauth', [function() {
        return  {id : 0}
}])
    .factory('invonew', [function() {
        return  {id : true}
    }])
.service('restAPI',  function($resource) {
    this.rest = function(url){
        return $resource(url + '/:id', { id: '@_id' }, {update: { method: 'PUT', params : {id : '@_id'}}})
    }
})
    
.directive('eafieldorder', function() {
        return {
            template: function (elem, attr ) {
                return '<label>' + attr.display + ' &nbsp;<i id="' + attr.idfs +'" ng-click="setorder(\'' + attr.field+ '\', \''+ attr.idfs + '\')" class="fa fa-sort mouse"></i></label>';
            }

        }
})

.directive('eafilter', function() {
        return {
            restrict: 'E',
            template: function (elem, attr ) {
                var caretstar = (attr.caret !== 'off')? '<div class="input-group input-group-sm">':'';
                var caretend = (attr.caret !== 'off') ?'<span class="input-group-addon" id="sizing-addon1"><i class="fa fa-search"></i></span></div>' :'';
                return caretstar +
                    '<input ng-class="{changefilter : filter.'+attr.field +' !== \'\'}" class="form-control input-sm" placeholder="buscar.." ng-model="filter.' + attr.field +'">'
                    + caretend;
            }

}

}).service('upload', ["$http", "$q", function ($http, $q)
    {
        this.uploadFile = function(file, name)
        {
            var deferred = $q.defer();
            var formData = new FormData();
            formData.append("name", name);
            formData.append("file", file);
            return $http.post("/config/imgstore", formData, {
                headers: {
                    "Content-type": undefined
                },
                transformRequest: angular.identity
            })
                .success(function(res)
                {
                    deferred.resolve(res);
                })
                .error(function(msg, code)
                {
                    deferred.reject(msg);
                });
            return deferred.promise;
        }
}])

.directive('uploaderModel', ["$parse", function ($parse) {
        return {
            restrict: 'A',
            link: function (scope, iElement, iAttrs)
            {
                iElement.on("change", function(e)
                {
                    $parse(iAttrs.uploaderModel).assign(scope, iElement[0].files[0]);
                });
            }
        };
}])

.filter('startFromGrid', function() {
        return function(input, start) {
            start =+ start;
            return input.slice(start);
        }
});



