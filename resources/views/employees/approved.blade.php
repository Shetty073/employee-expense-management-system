@extends('adminlte::page')

@section('title', 'List Of Approved Vouchers')

@section('content_header')
    <h1>List Of Approved Vouchers</h1>
@stop

@section('content')
    <div class="card px-3 py-1">
        <input type="text" id="searchBox" placeholder="🔍 Search the table below">
        <br>

        <div class="table-responsive">
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Employee Name</th>
                        <th scope="col">Job Numbers</th>
                        <th scope="col">Voucher Number</th>
                        <th scope="col">Voucher Date</th>
                        <th scope="col">Proposed Amount</th>
                        <th scope="col">Approved Amount</th>
                        <th scope="col">Approved On</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($vouchers as $voucher)
                    <?php
                        $expenses = $voucher->expenses;
                        $total_amt = 0.0;
                        foreach ($expenses as $exp) {
                            $total_amt += $exp->amount;
                        }

                    ?>
                    <tr>
                        <td>{{ $voucher->employee()->first()->name }}</td>
                        <td>
                            @foreach ($voucher->jobs as $job)
                                {{ $job->number }}@if(!$loop->last), @endif
                            @endforeach
                        </td>
                        <td>{{ $voucher->number }}</td>
                        <td>{{ $voucher->date->format('d-M-Y') }}</td>
                        <td>
                            <span class="badge badge-warning px-2 py-2">
                                ₹ {{ $total_amt }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-success px-2 py-2">
                                ₹ {{ $voucher->approved_amount }}
                            </span>
                        </td>
                        <td>{{ $voucher->approval_date->format('d-M-Y') }}</td>
                        <td>
                            <a class="btn btn-primary" href="{{ route('employees.voucherDetails', ['id' => $voucher->id]) }}">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

@stop

{{-- @section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop --}}

@section('js')
    <script src="{{ asset('js/tableFilter.js') }}"></script>
@stop
