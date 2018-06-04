@extends('admin.layouts.app')

@section('content')
@include('admin.partials.status')

{!! Form::open(['url' => route('admin:login:get')]) !!}
    <div class="form">
        <div class="label">Mobile No.</div>
        <div class="input">{!! Form::text('mobile', old('mobile'), ['required']) !!}</div>
    </div>
    <div class="form">
        <div class="label">Password</div>
        <div class="input">{!! Form::password('password', ['required']) !!}</div>
    </div>
    <div class="form">
        <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}> <label for="remember">Remember Me</label>
    </div>
    <div class="form">
        {!! Form::submit('Login') !!}
    </div>
{!! Form::close() !!}
@stop