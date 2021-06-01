@extends('adminlte::page')

@section('title', 'Vouchers')

@section('content_header')
    <h1>Vouchers</h1>
@stop

@section('content')
    <p>Your wallet details are visible here.</p>

    <div class="card px-3 py-1">
        <h2>Your total wallet banalce is:
            <span class="badge badge-primary px-2 py-2">
                ₹ {{ auth()->user()->employee->wallet_balance }}
            </span>
        </h2>

        <br>

        <div class="table-responsive">
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Payment Number</th>
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
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop --}}
