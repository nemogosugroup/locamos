<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="locale" content="{{ app()->getLocale() }}"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" value="{{ csrf_token() }}"/>
    <title>Gosu</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="{{ mix('css/app.css') }}" type="text/css" rel="stylesheet"/>
    <link rel="icon" type="image/x-icon" href="{{ mix('/images/logo/GOSU_icon.png') }}">
</head>
<body>
<div id="app">
</div>
<script src="{{ mix('js/app.js') }}" type="text/javascript"></script> 
<script src="{{ asset('backend/js/ckfinder/ckfinder.js') }}" ></script>
</body>
</html>