<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{$_ENV['APP_NAME']}}</title>
    <meta name="description" content="">
    <link href="{{ asset('css/choices.min.css') }}" rel="stylesheet" />
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <link href="{{ asset('css/libs.min.css') }}" rel="stylesheet">
    <script src="{{asset('js/choices.min.js')}}"></script>
    <link rel="icon" type="image/x-icon" href="/favicons/favicon.ico">
    <meta name="apple-mobile-web-app-title" content="BoostMe">
    <meta name="application-name" content="BoostMe">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/favicons/mstile-144x144.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <meta name="theme-color" content="#ffffff">

</head>

<body>
    <div id="app">@yield('content')</div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script src="{{ mix('js/app.js') }}" defer></script>
    <script src="{{asset('js/libs.js')}}"></script>
    <script src="{{asset('js/main.js')}}"></script>
</body>

</html>
