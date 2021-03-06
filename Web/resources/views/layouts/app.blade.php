<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Virtual Web') }}</title>

    <!-- Styles -->
    <link href='https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en' rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="{{ asset('css/material.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/getmdl-select.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/notyf.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/animate.css') }}" rel="stylesheet">
    <link rel="icon" href="img/favicon.ico">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="{{ Route::currentRouteName() }}">
    <div id="app">

    </div>
</body>
<!-- Scripts -->

<script type="text/javascript">
    var dataArray = {!! $dataToShow !!}
</script>
<script src="{{ asset('js/app.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/getmdl-select.min.js') }}"></script>
<script src="{{ asset('js/notyf.min.js') }}"></script>
<script type="text/javascript">
    var notyf = new Notyf();
</script>
<script src="{{ asset('js/material.min.js') }}"></script>
</html>
