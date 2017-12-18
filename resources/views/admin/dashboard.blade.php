@extends('admin.layouts.app')

@section('content')
<h2>Welcome {{ Auth::user()->name }}</h2>
@stop