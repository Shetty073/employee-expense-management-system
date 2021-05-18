@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Create New Employee</h1>
@stop

@section('content')

<div class="card px-3 py-1">

    <form method="POST" action="{{ route('employees.store') }}">
        @include('employees.form')
    </form>

</div>

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop
