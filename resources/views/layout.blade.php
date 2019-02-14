<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>PrestaGes</title>
    <!-- Estilos -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/select.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <link href="css/bootstrap-datetimepicker.css" rel="stylesheet">
    <link href="css/toastr.css" rel="stylesheet">
    <!-- Scripts -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/toastr.min.js"></script>
    <script src="js/angular.min.js"></script>
    <script src="js/angular-resource.min.js"></script>
    <script src="js/angular-sanitize.min.js"></script>
    <script src="js/underscore.js"></script>
    <script src="js/select.min.js"></script>
    <script src="app/presujet.js"></script>
    <script src="app/header.js"></script>
    <script src="app/utiles.js"></script>
    <script src="js/moment.js"></script>
    <script src="js/bootstrap-datetimepicker.min.js"></script>
    @yield('scripts')
</head>
<body ng-app="presujet">
    @include('app.header')
    @yield('content')
    @include('app.footer')
</body>
</html>