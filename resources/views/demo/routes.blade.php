@extends('layouts.app')

@section('content')
<div class="content" id="demo_routes">
    <h1 class="head">Routes</h1>
    <div>For post routes contact developer.<br/>API Endpoint <span class="code">{{ env('APP_URL') }}</span></div>
    @foreach ($categorize as $categoryTitle => $categoryData)
        <h2 class="head">{{ strtoupper($categoryTitle) }}</h2>
        <div class="grouped-routes">
        @foreach ($categoryData as $categoryTitle2 => $routes)
            @if (is_object($routes))
                @include('partials.route-partial', [
                    'route' => $routes
                ])
            @else
                <h3 class="grouped-sub-title">{{ ucwords(str_replace('-', ' ', $categoryTitle2)) }}</h3>
                @foreach ($routes as $route)
                    @include('partials.route-partial', [
                        'route' => $route
                    ])
                @endforeach
            @endif
        @endforeach
        </div>
    @endforeach
</div>
@stop

@section('header')
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}"/>
@stop