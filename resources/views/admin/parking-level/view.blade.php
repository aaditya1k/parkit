@inject('parkingService', 'App\Services\ParkingService')
@extends('admin.layouts.app')

@section('content')
    <div class="view">
        <div class="label">Group</div>
        <div class="input">{{ $parkingLevel->parking->group->name }}</div>
    </div>

    <div class="view">
        <div class="label">Parking</div>
        <div class="input">{{ $parkingLevel->parking->label }}</div>
    </div>

    <div class="view">
        <div class="label">Label</div>
        <div class="input">{{ $parkingLevel->label }}</div>
    </div>

    <div class="view">
        <div class="label">Grid</div>
        <div class="input">{{ $parkingLevel->grid_row }}x{{ $parkingLevel->grid_row }}</div>
    </div>

    <div class="cf">
    <div class="gen-map gen-map-view">
    <?php
        $generateMap = $parkingService->generateMap(
            $parkingLevel->grid_row,
            $parkingLevel->grid_col,
            json_decode($parkingLevel->grid_map)
        );
    ?>
    {!! $generateMap['html'] !!}
    </div>
    </div>

    <div class="view">
        <div class="label">Created</div>
        <div class="input">{{ $parkingLevel->createdAt() }}</div>
    </div>

    <div class="view">
        <div class="label">Updated</div>
        <div class="input">{{ $parkingLevel->updatedAt() }}</div>
    </div>
@stop

@section('header')
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}"/>
@stop