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

{{-- @section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop --}}

{{-- @section('js')
    <script src="{{ asset('js/voucherform.js') }}"></script>
@stop --}}
