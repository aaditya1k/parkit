<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Park It</title>
<link href="{{ mix('css/app.css') }}" rel="stylesheet" type="text/css"/>
{{-- <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet"> --}}
@section('header')
@show
</head>
<body>
@yield('content')
<script src="{{ mix('js/app.js') }}"></script>
@section('footer')
@show
</body>
</html>