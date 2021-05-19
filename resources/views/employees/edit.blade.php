@extends('adminlte::page')

@section('title', 'Employee Master - Edit Employee')

@section('content_header')
    <h1>Edit Employee</h1>
@stop

@section('content')

<div class="card px-3 py-1">

    <form method="POST" action="{{ route('employees.update', ['id' => $employee->id]) }}" enctype="multipart/form-data">
        @include('employees.form')
    </form>

</div>

@stop


{{-- @section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop --}}
