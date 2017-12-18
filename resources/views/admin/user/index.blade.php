@extends('admin.layouts.app')

@section('content')
    <table class="data">
        <thead>
            <tr>
                <th>ID</th>
                <th>Mobile</th>
                <th>Name</th>
                <th>Is Active?</th>
                <th>Is Admin?</th>
                <th>Joined</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td><span class="show-value" data-value="{{ $user->mobile }}"></span></td>
                <td>{{ $user->name }}</td>
                <td>{!! $user->is_active ? 'Yes' : '<span class="no">No</span>' !!}</td>
                <td>{!! $user->is_admin ? 'Yes' : '<span class="no">No</span>' !!}</td>
                <td>{{ $user->createdAt() }}</td>
                <td class="option">
                    <a href="{{ route('admin:user:view', $user->id) }}">View</a>
                    <a href="{{ route('admin:user:edit', $user->id) }}">Edit</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {!! $users->render() !!}
@stop