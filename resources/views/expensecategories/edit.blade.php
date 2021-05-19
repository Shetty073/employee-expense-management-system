@extends('adminlte::page')

@section('title', 'Expense Category Master - Edit Expense Category')

@section('content_header')
    <h1>Edit Expense Category</h1>
@stop

@section('content')

<div class="card px-3 py-1">

    <form method="POST" action="{{ route('expensecategories.update', ['id' => $expensecategory->id]) }}">
        @include('expensecategories.form')
    </form>

</div>

@stop


{{-- @section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop --}}
