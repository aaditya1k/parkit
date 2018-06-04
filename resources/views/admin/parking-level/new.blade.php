@extends('admin.layouts.app')

@section('content')
    @include('admin.partials.status')

    <h2 class="head">New Parking Level</h2>

    {!! Form::open(['url' => route('admin:parking-level:create', $parking->id), 'id' => 'admin-parking-level-form']) !!}
        <div class="view">
            <div class="label">Group</div>
            <div class="input">{{ $parking->group->name }}</div>
        </div>

        <div class="view">
            <div class="label">Parking</div>
            <div class="input">{{ $parking->label }}</div>
        </div>

        <div class="form">
            <div class="label">Level Label</div>
            <div class="input">
                {!! Form::text('label', old('label')) !!}
            </div>
        </div>

        <div class="form">
            <div class="label">Grid Row x Col</div>
            <div class="input">
                {!! Form::text('grid_row', old('grid_row'), ['id' => 'grid-row']) !!}
                x
                {!! Form::text('grid_col', old('grid_col'), ['id' => 'grid-col']) !!}
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
                <textarea name="grid_console" placeholder="Grid Console" id="grid-input"></textarea>
            </div>
        </div>

        <div class="form">
            {!! Form::submit('Create') !!}
        </div>
    {!! Form::close() !!}
@stop

@section('header')
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}"/>
    <script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
@stop