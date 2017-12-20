@extends('admin.layouts.app')

@section('content')
<h2>Welcome back {{ Auth::user()->name }}</h2>
@stop