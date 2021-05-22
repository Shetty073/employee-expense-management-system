@extends('adminlte::page')

@section('title', 'List Of Approved Vouchers')

@section('content_header')
    <h1>List Of Approved Vouchers</h1>
@stop

@section('content')
    <div class="card px-3 py-1">

        <div class="table-responsive">
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Employee Name</th>
                        <th scope="col">Date</th>
                        <th scope="col">Total Amount</th>
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
                        <td>{{ $voucher->date }}</td>
                        <td>
                            <span class="badge badge-primary px-2 py-2">
                                ₹ {{ $total_amt }}
                            </span>
                        </td>
                        <td>
                            <a class="dropdown-item text-primary" href="{{ route('employees.voucherDetails', ['id' => $voucher->id]) }}">View</a>
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
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop --}}
