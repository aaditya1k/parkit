@extends('admin.layouts.app')

@section('content')
    <div class="view">
        <div class="label">Mobile</div>
        <div class="input">{{ $user->mobile }}</div>
    </div>

    <div class="view">
        <div class="label">Name</div>
        <div class="input">{{ $user->name }}</div>
    </div>

    <div class="view">
        <div class="label">Is Active?</div>
        <div class="input">{!! $user->is_active ? 'Yes' : '<span class="no">No</span>' !!}</div>
    </div>

    <div class="view">
        <div class="label">Is Admin?</div>
        <div class="input">{!! $user->is_admin ? 'Yes' : '<span class="no">No</span>' !!}</div>
    </div>

    <div class="view">
        <div class="label">Joined</div>
        <div class="input">{{ $user->createdAt() }}</div>
    </div>

    <div class="view">
        <div class="label">Last updated</div>
        <div class="input">{{ $user->updatedAt() }}</div>
    </div>
@stop