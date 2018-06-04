<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Admin{{ $pageTitle != null ? ' - '.$pageTitle : null }}</title>
<link href="{{ mix('css/admin-app.css') }}" rel="stylesheet" type="text/css"/>
{{--  <meta name="csrf-token" content="{{ csrf_token() }}">  --}}
@section('header')
@show
</head>
<body>

@if (Auth::check())
    <div id="sidebar">{!! $nav !!}</div>
    <div id="main-content">
        <div id="user">
            <span class="name">{{ Auth::user()->name }}</span>
            <a href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a>
            {!! Form::open(['url' => route('admin:login:logout'), 'style' => 'display:none', 'id' => 'logout-form']) !!}
            {!! Form::close() !!}
        </div>
        <div id="content">
            <h1 class="head">{{ $pageTitle }}</h1>
            @yield('content')
        </div>
    </div>
@else
    <div id="login">
        <h1 class="head">Login</h1>
        @yield('content')
    </div>
@endif

<script src="{{ mix('js/admin-app.js') }}"></script>
@section('footer')
@show
</body>
</html>