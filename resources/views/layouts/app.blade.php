<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <!--css-->
    <link rel="shortcut icon" href="{{asset('assets/images/favicon.png')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/font-awesome.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/bootstrap.min.css')}}">
    <!-- <link rel="stylesheet" type="text/css" href="{{asset('assets/css/owl.carousel.min.css')}}"> -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/slick.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/simplebar.css')}}">
    <link rel="stylesheet" type="text/css" href="assets/css/animate.css">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/style.css')}}">
</head>
<body>
<div class="admin-wrapper content-wrapper" data-simplebar>
		<div class="container h-100">
			<div class="row-login">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="{{asset('assets/js/jquery-2.2.4.js')}}"></script>
    <script src="{{asset('assets/js/popper.min.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap.min.js')}}"></script>
    <!-- <script src="{{asset('assets/js/owl.carousel.min.js')}}"></script> -->
    <script src="{{asset('assets/js/slick.min.js')}}"></script>
    <script src="{{asset('assets/js/simplebar.js')}}"></script>
    <script src="{{asset('assets/js/custom.js')}}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var elements = document.getElementsByTagName("INPUT");
            for (var i = 0; i < elements.length; i++) {
                elements[i].oninvalid = function (e) {
                    e.target.setCustomValidity("");
                    if (!e.target.validity.valid) {
                        e.target.setCustomValidity("Please fill out this field");
                    }
                };
                elements[i].oninput = function (e) {
                    e.target.setCustomValidity("");
                };
            }
        })
    </script>
    @yield('js')
</body>
</html>
