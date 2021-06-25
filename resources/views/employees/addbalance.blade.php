@extends('adminlte::page')

@section('title', 'Add Balance To Employee\'s Wallet')

@section('content_header')
    <h1>Add Balance To {{ $employee->name }}'s Wallet</h1>
@stop

@section('content')

<div class="card px-3 py-1">

    <form id="addBalanceForm" method="POST" action="{{ route('employees.addbalance', ['id' => $employee->id]) }}" enctype="multipart/form-data">
        @if ($errors->any())
            <div class="border border-danger text-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @csrf

        <div class="row">
            <div class="form-group col-sm-4">
                <label for="new_wallet_balance">Balance</label>
                <input type="number" class="form-control" id="new_wallet_balance" name="new_wallet_balance"
                value="{{ $employee->wallet_balance }}" disabled>
            </div>
            <div class="form-group col-sm-4">
                <label for="wallet_balance">Add Amount</label>
                <input type="number" class="form-control" id="wallet_balance" name="wallet_balance" required>
            </div>
            <div class="form-group col-sm-4">
                <label for="payment_mode">Payment Mode</label>
                <Select class="form-control" id="payment_mode" name="payment_mode">
                    <option value="0">{{ App\Accunity\Utils::PAYMENT_MODES[0] }}</option>
                    <option value="1">{{ App\Accunity\Utils::PAYMENT_MODES[1] }}</option>
                    <option value="2">{{ App\Accunity\Utils::PAYMENT_MODES[2] }}</option>
                    <option value="3">{{ App\Accunity\Utils::PAYMENT_MODES[3] }}</option>
                </Select>
            </div>

        </div>
        <div class="row">
            <div class="form-group col-sm-4">
                <label for="date">Date</label>
                <input type="date" class="form-control" id="date" name="date"
                value="{{ Carbon\Carbon::now()->format('Y-m-d') }}" required>
            </div>
            <div class="form-group col-sm-4">
                <label for="remark">Remark</label>
                <input type="text" class="form-control" id="remark" name="remark">
            </div>
        </div>

        <div class="form-group mt-3">
            <input id="addBalanceBtn" type="button" class="btn btn-success" value="Add">
            <input type="button" class="btn btn-danger ml-3" value="Cancel" onclick="window.history.back()">
        </div>

    </form>

</div>

@stop


@section('js')
    <script src="{{ asset('js/addbalance.js') }}"></script>
@stop

