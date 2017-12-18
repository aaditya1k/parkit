@extends('admin.layouts.app')

@section('content')
    @include('admin.parking-level.index-partial')
    {!! $parkingLevels->render() !!}
@stop