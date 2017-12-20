@extends('admin.layouts.app')

@section('content')
    <div class="options">
        <a href="{{ route('admin:group:new') }}">Create Group</a>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Balance &#8377;</th>
                <th>Created</th>
                <th>Updated</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @foreach ($groups as $group)
            <tr>
                <td>{{ $group->id }}</td>
                <td>{{ $group->name }}</td>
                <td>{{ $group->balance }} &#8377;</td>
                <td>{{ $group->createdAt() }}</td>
                <td>{{ $group->updatedAt() }}</td>
                <td class="option">
                    <a href="{{ route('admin:group:view', $group->id) }}">View</a>
                    <a href="{{ route('admin:group:edit', $group->id) }}">Edit</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {!! $groups->render() !!}
@stop