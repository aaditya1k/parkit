@extends('admin.layouts.app')

@section('content')
    @include('admin.partials.status')

    <h2 class="head">Edit</h2>

    {!! Form::open(['url' => route('admin:group:update', $group->id)]) !!}
        <div class="form">
            <div class="label">Name</div>
            <div class="input">{!! Form::text('name', $group->name) !!}</div>
        </div>
        <div class="form">
            {!! Form::submit('Update') !!}
        </div>
    {!! Form::close() !!}
@stop