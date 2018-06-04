@extends('admin.layouts.app')

@section('content')
    @include('admin.partials.status')

    <h2 class="head">Edit Parking Level</h2>

    {!! Form::open(['url' => route('admin:parking-level:update', $parkingLevel->id), 'id' => 'admin-parking-level-form']) !!}
        <div class="view">
            <div class="label">Group</div>
            <div class="input">{{ $parkingLevel->parking->group->name }}</div>
        </div>

        <div class="view">
            <div class="label">Parking</div>
            <div class="input">{{ $parkingLevel->parking->label }}</div>
        </div>

        <div class="form">
            <div class="label">Level Label</div>
            <div class="input">
                {!! Form::text('label', old('label', $parkingLevel->label)) !!}
            </div>
        </div>

        <div class="form">
            <div class="label">Grid Row x Col</div>
            <div class="input">
                {!! Form::text('grid_row', old('grid_row', $parkingLevel->grid_row), ['id' => 'grid-row']) !!}
                x
                {!! Form::text('grid_col', old('grid_col', $parkingLevel->grid_col), ['id' => 'grid-col']) !!}
                <button id="grid-regenrate">Generate Grid</button>
            </div>
        </div>

        <div class="form">
            <div class="label">Grid</div>
            <div class="input">
                <div class="cf"><div id="gen-map-tools"></div></div>
                <br/>
                <div class="cf"><div id="gen-map-tiles" class="gen-map"></div></div>
                <br/>
                <button id="grid-save-new">Save Grid</button>
            </div>
            <div class="input">
                <?php
                    $data = json_encode([
                        'rows' => $parkingLevel->grid_row,
                        'cols' => $parkingLevel->grid_col,
                        'map' => json_decode($parkingLevel->grid_map)
                    ]);
                ?>
                <textarea name="grid_console" placeholder="Grid Console" id="grid-input">{{ $data }}</textarea>
            </div>
        </div>

        <div class="form">
            {!! Form::submit('Update') !!}
        </div>
    {!! Form::close() !!}
@stop

@section('header')
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}"/>
    <script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
@stop

@section('footer')
    <script>
        $(document).ready(function() {
            document.getElementById("grid-input").dispatchEvent(new Event('change'));
        });
    </script>
@stop