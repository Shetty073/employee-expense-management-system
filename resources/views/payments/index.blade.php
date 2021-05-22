@extends('adminlte::page')

@section('title', 'Payments')

@section('content_header')
    <h1>Payments</h1>
@stop

@section('content')

    <p class="float-left">List of all payments is visible here.</p>

    <br><br><br>

    <div class="card px-3 py-1">

        <div class="table-responsive">
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Employee Name</th>
                        <th scope="col">Total Amount</th>
                        <th scope="col">Payment Mode</th>
                        <th scope="col">Date</th>
                        <th scope="col">Remark</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payments as $payment)
                    <tr>
                        <td style="font-weight: 700;">{{ $payment->employee()->first()->name }}</td>
                        <td>
                            <span class="badge badge-primary px-2 py-2" style="font-size: 1rem;">
                                ₹ {{ $payment->amount }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-warning px-2 py-2" style="font-size: 1rem;">
                                <i class="fas fa-{{ App\Accunity\Utils::PAYMENT_MODES_ICONS[$payment->payment_mode] }}"></i>
                                {{ App\Accunity\Utils::PAYMENT_MODES[$payment->payment_mode] }}
                            </span>
                        </td>
                        <td style="font-weight: 600;">
                            {{ $payment->date->format('d-m-Y') }}
                        </td>
                        <td style="font-weight: 600;">
                            {{ $payment->remark }}
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
