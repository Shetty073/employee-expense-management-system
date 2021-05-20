@extends('adminlte::page')

@section('title', 'Edit Voucher')

@section('content_header')
    <h1>Edit Voucher</h1>
@stop

@section('content')

<div class="card px-3 py-1">

    <form method="POST" action="{{ route('vouchers.update', ['id' => $voucher->id]) }}">
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
                <label for="job">Select Job</label>
                <select class="custom-select" id="job" name="job[]" multiple>
                    @foreach ($jobs as $job)
                        <option value="{{ $job->id }}">{{ $job->number }} - {{ $job->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-sm-4">
                <label for="voucherdate">Voucher Date</label>
                <input type="date" class="form-control" id="voucherdate" name="voucherdate" value="{{ Carbon\Carbon::now()->format('Y-m-d') }}"
                value="@if(isset($voucher)){{ $voucher->voucherdate }}@else{{ old('voucherdate') }}@endif" required>
            </div>
        </div>
    </form>

    {{-- TODO: Here add foreach loop for earlier created expenses with update and delete buttons --}}

    <form method="POST" action="{{ route('vouchers.createExpense') }}" enctype="multipart/form-data">
        <div id="expenses">
            <div class="row" >
                <div class="form-group col-sm-2">
                    <label for="date">Expense Date</label>
                    <input type="date" class="form-control" id="date" name="date[]"
                    value="@if(isset($expense)){{ $expense->date }}@else{{ old('date') }}@endif" required>
                </div>
                <div class="form-group col-sm-3">
                    <label for="category">Expense Category</label>
                    <input type="text" class="form-control" id="category" name="category[]" required>
                </div>
                <div class="form-group col-sm-3">
                    <label for="description">Expense Description</label>
                    <textarea type="text" class="form-control" id="description" name="description[]" required></textarea>
                </div>
                <div class="form-group col-sm-2">
                    <label for="bill">Expense Bill</label>
                    <input type="file" class="form-control" id="bill" name="bill[]">
                </div>
                <div class="form-group col-sm-2">
                    <label for="amount">Expense Amount</label>
                    <input type="number" class="form-control" id="amount" name="amount[]">
                </div>
            </div>
        </div>

        <div class="row">
            <input type="submit" class="btn btn-success" value="Save">
            @if($voucher->status !== 2)
                <input type="button" id="deleteExpenseBtn" class="btn btn-danger ml-3" value="Delete">
            @endif
        </div>
    </form>

    <div class="form-group mt-3">
        <input type="button" id="applyForApprovalBtn" class="btn btn-primary" value="Apply For Approval">
        <input type="button" class="btn btn-danger ml-3" value="Cancel" onclick="window.history.back()">
    </div>

</div>

@stop


{{-- @section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop --}}

{{-- @section('js')
    <script src="{{ asset('js/voucherform.js') }}"></script>
@stop --}}
