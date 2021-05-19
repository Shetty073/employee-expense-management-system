@extends('adminlte::page')

@section('title', 'Job Master - Edit Job Details')

@section('content_header')
    <h1>Edit Job Details</h1>
@stop

@section('content')

<div class="card px-3 py-1">

    <form method="POST" action="{{ route('jobs.update', ['id' => $job->id]) }}">
        @include('jobs.form')
    </form>

</div>

@stop


{{-- @section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop --}}
