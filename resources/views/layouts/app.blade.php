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
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script> --}}
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script src="https://unpkg.com/@google/markerclustererplus@4.0.1/dist/markerclustererplus.min.js"></script>
    {{-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAS8pvTzC3TNIQI0t30crTalS0L8F1ST28&libraries=&v=weekly"></script> --}}
    {{-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAS8pvTzC3TNIQI0t30crTalS0L8F1ST28"></script> --}}
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