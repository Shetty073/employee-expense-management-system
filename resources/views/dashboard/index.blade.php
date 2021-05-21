@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')

@if(auth()->user()->is_admin)
    {{-- Employee dashboard --}}

@else
    {{-- Dashboard for employee --}}
    <div class="row">
        <div class="col-sm-3">
            <div class="card px-3 py-1 bg-success text-white" style="height: 120px;">
                <span class="ml-auto">Wallet Balance</span>
                <span class="ml-auto" style="font-size: 60px;">
                    ₹ {{ auth()->user()->employee->wallet_balance }}
                </span>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card px-3 py-1 bg-primary text-white" style="height: 120px;">
                <span class="ml-auto">Total Vouchers</span>
                <span class="ml-auto" style="font-size: 60px;">
                    {{ auth()->user()->employee->vouchers()->get()->count() }}
                </span>
            </div>
        </div>
        <div class="col-sm-3"></div>
    </div>
@endif

@stop
