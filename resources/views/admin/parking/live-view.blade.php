@inject('parkingService', 'App\Services\ParkingService')
@extends('admin.layouts.app')

@section('content')
    <?php
        $carNumber = 0;
        $bikeNumber = 0;
    ?>
    @foreach ($parkingLevels as $parkingLevel)
        <br/>
        <h2>{{ $parkingLevel->label }}</h2>
        <div class="cf">
            <div class="gen-map gen-map-view">
                <?php
                    $generateMap = $parkingService->generateMap(
                        $parkingLevel->grid_row,
                        $parkingLevel->grid_col,
                        json_decode($parkingLevel->grid_map),
                        $carNumber,
                        $bikeNumber,
                        $parking->parkedList
                    );
                    $carNumber = $generateMap['carNumber'];
                    $bikeNumber = $generateMap['bikeNumber'];
                ?>
                {!! $generateMap['html'] !!}
            </div>
        </div>
    @endforeach
@stop

@section('header')
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}"/>
@stop