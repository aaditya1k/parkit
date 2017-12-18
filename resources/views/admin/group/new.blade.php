@extends('admin.layouts.app')

@section('content')
    @include('admin.partials.status')

    <h2 class="head">New</h2>

    {!! Form::open(['url' => route('admin:group:create')]) !!}
        <div class="form">
            <div class="label">Name</div>
            <div class="input">{!! Form::text('name') !!}</div>
        </div>
        <div class="form">
            {!! Form::submit('Create') !!}
        </div>
    {!! Form::close() !!}
@stop