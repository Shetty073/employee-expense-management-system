@extends('adminlte::page')

@section('title', 'Vouchers')

@section('content_header')
    <h1>Vouchers</h1>
@stop

@section('content')
    <p>Your wallet details are visible here.</p>

    <div>
        <h4>You wallet banalce is:
            <span class="badge badge-primary">
                ₹ {{ auth()->user()->employee->wallet_balance }}
            </span>
        </h4>
    </div>
@stop

{{-- @section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop --}}
