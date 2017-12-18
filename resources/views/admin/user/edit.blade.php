@inject('groupService', 'App\Services\GroupService')
@inject('parkingService', 'App\Services\ParkingService')
@extends('admin.layouts.app')

@section('content')
    @include('admin.partials.status')

    <h2 class="head">Edit</h2>

    {!! Form::open(['url' => route('admin:user:update', $user->id)]) !!}
        <div class="form">
            <div class="label">Mobile</div>
            <div class="input">{{ $user->mobile }}</div>
        </div>

        <div class="form">
            <div class="label">Name</div>
            <div class="input">{{ $user->name }}</div>
        </div>

        <div class="form">
            <div class="label">Is Admin?</div>
            <div class="input">
                {!! Form::select('is_admin', [
                    0 => 'No',
                    1 => 'Yes'
                ], old('is_admin', $user->is_admin)) !!}
            </div>
        </div>

        <div class="form">
            <div class="label">
                New Password
                <div class="label-desc">(leave blank if no change)</div>
            </div>
            <div class="input">
                {!! Form::password('new_password') !!}
            </div>
        </div>

        <div class="form">
            {!! Form::submit('Update') !!}
        </div>
    {!! Form::close() !!}
@stop