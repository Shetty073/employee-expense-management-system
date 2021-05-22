@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Create New Employee</h1>
@stop

@section('content')

<div class="card px-3 py-1">

    <form method="POST" action="{{ route('employees.store') }}" enctype="multipart/form-data">
        @include('employees.form')
    </form>

</div>

@stop

@section('js')
    <script src="{{ asset('js/addEmployee.js') }}"></script>
@stop
