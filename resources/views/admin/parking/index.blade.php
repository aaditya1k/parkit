@extends('admin.layouts.app')

@section('content')
    <div class="options">
        <a href="{{ route('admin:parking:new') }}">Create Parking</a>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th>ID</th>
                <th>Group</th>
                <th>Label</th>
                <th>Created</th>
                <th>Updated</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @foreach ($parkings as $parking)
            <tr>
                <td>{{ $parking->parkingId() }}</td>
                <td>{{ $parking->group->name }}</td>
                <td>{{ $parking->label }}</td>
                <td>{{ $parking->createdAt() }}</td>
                <td>{{ $parking->updatedAt() }}</td>
                <td class="option">
                    <a href="{{ route('admin:parking:view', $parking->id) }}">View</a>
                    <a href="{{ route('admin:parking:edit', $parking->id) }}">Edit</a>
                    <a href="{{ route('admin:parking-level:new', $parking->id) }}">Create Level</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {!! $parkings->render() !!}
@stop