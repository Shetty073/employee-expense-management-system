@extends('adminlte::page')

@section('title', 'Create New Voucher')

@section('content_header')
    <h1>Create New Voucher</h1>
@stop

@section('content')

<div class="card px-3 py-1">

    <form method="POST" action="{{ route('vouchers.store') }}">
        @include('vouchers.form')
    </form>

</div>

@stop

@section('js')
    <script src="{{ asset('js/s2.js') }}"></script>
@stop

