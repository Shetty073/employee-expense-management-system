@extends('adminlte::page')

@section('title', 'Payments')

@section('content_header')
    <h1>Payments</h1>
@stop

@section('content')

    <p class="float-left">List of all payments is visible here.</p>

    <br><br><br>

    <div class="card px-3 py-1">
        <input type="text" id="searchBox" placeholder="🔍 Search the table below">
        <br>

        <div class="table-responsive">
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Payment Number</th>
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
                        <td>
                            <b style="font-size: 1rem;">
                                @if(strpos($payment->remark, 'Voucher Accepted') !== false)
                                    {{ $payment->employee->code }}-{{ $payment->id }}
                                @else
                                    P-{{ $payment->employee->code }}-{{ $payment->id }}
                                @endif
                            </b>
                        </td>
                        <td style="font-weight: 700;">{{ $payment->employee->name }}</td>
                        <td>
                            @if(strpos($payment->remark, 'Voucher Accepted') !== false)
                                <span class="badge badge-success px-2 py-2" style="font-size: 1rem;">
                                    ₹ -{{ $payment->amount }}
                                </span>
                            @else
                                <span class="badge badge-primary px-2 py-2" style="font-size: 1rem;">
                                    ₹ {{ $payment->amount }}
                                </span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-warning px-2 py-2" style="font-size: 1rem;">
                                @if(strpos($payment->remark, 'Voucher Accepted') !== false)
                                    Voucher Accepted
                                @else
                                    <i class="fas fa-{{ App\Accunity\Utils::PAYMENT_MODES_ICONS[$payment->payment_mode] }}"></i>
                                    {{ App\Accunity\Utils::PAYMENT_MODES[$payment->payment_mode] }}
                                @endif
                            </span>
                        </td>
                        <td style="font-weight: 600;">
                            {{ $payment->date->format('d-M-Y') }}
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
@stop --}}

@section('js')
    <script src="{{ asset('js/tableFilter.js') }}"></script>
@stop
