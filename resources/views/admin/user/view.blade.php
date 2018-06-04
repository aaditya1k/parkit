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
        <div class="label">Balance &#8377;</div>
        <div class="input">{{ $user->balance }} &#8377;</div>
    </div>

    <div class="view">
        <div class="label">Joined</div>
        <div class="input">{{ $user->createdAt() }}</div>
    </div>

    <div class="view">
        <div class="label">Last updated</div>
        <div class="input">{{ $user->updatedAt() }}</div>
    </div>

    <h2 class="head">Activity</h2>
    <table class="data">
        <thead>
            <tr>
                <th>Type</th>
                <th>Data 1</th>
                <th>Data 2</th>
                <th>Created</th>
            </tr>
        </thead>
    @foreach ($user->activity as $activity)
        <tr>
            <td>{{ $activity->type }}</td>
            <td>{{ $activity->data1 }}</td>
            <td>{{ $activity->data2 }}</td>
            <td>{{ $activity->created_at }}</td>
        </tr>
    @endforeach
    </table>
@stop