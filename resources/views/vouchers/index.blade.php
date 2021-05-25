@extends('adminlte::page')

@section('title', 'Vouchers')

@section('content_header')
    <h1>Vouchers</h1>
@stop

@section('content')

    <p class="float-left">List of all of your vouchers is visible here.</p>
    <div class="float-right">
        <a href="{{ route('vouchers.create') }}" class="btn btn-primary">+ Create New Voucher</a>
    </div>

    <br><br><br>

    <div class="card px-3 py-1">

        <div class="table-responsive">
            <table class="table">
                <thead class="thead-dark">
                    <tr>
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
                        <td>{{ $voucher->date->format('d-M-Y') }}</td>
                        <td>
                            <span class="badge badge-primary px-2 py-2">
                                ₹ {{ $total_amt }}
                            </span>
                        </td>
                        <td>
                            <div class="dropdown">
                                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    ACTIONS
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item text-primary" href="{{ route('vouchers.edit', ['id' => $voucher->id]) }}">Edit</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item text-danger" href="{{ route('vouchers.destroy', ['id' => $voucher->id]) }}">Delete</a>
                                </div>
                            </div>
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
