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
                <select class="form-control js-example-basic-multiple" id="job" name="job[]" multiple="multiple">
                    <?php
                        $vouchers = $voucher->jobs()->get();
                        $voucherids = array();
                        foreach ($vouchers as $v) {
                            array_push($voucherids, $v->id);
                        }
                    ?>
                    @foreach ($jobs as $job)
                        <option value="{{ $job->id }}" @if(isset($voucher)) @if(in_array($job->id, $voucherids)) selected @endif @endif>
                            {{ $job->number }} - {{ $job->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        @if($voucher->status === 0)
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Update">
            </div>
        @endif

    </form>

    <br><br>

    @if($voucher->status === 0)
        <form id="insertForm" method="POST" action="{{ route('vouchers.createExpense', ['id' => $voucher->id]) }}" enctype="multipart/form-data">
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

            <div class="row" >
                <div class="form-group col-sm-2">
                    <label for="date">Expense Date</label>
                    <input type="date" class="form-control" id="date" name="date"
                    value="@if(isset($expense)){{ $expense->date->format('d-M-Y') }}@else{{ old('date') }}@endif" required>
                </div>
                <div class="form-group col-sm-3">
                    <label for="category">Expense Category</label>
                    <select class="form-control" id="category" name="category" required>
                        @foreach ($expenseCategories as $category)
                            <option value="{{ $category->id }}">
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-sm-2">
                    <label for="amount">Expense Amount</label>
                    <input type="number" class="form-control" id="amount" name="amount">
                </div>
                <div class="form-group col-sm-2">
                    <label for="bill">Expense Bill</label>
                    <input type="file" class="form-control" id="bill" name="bill[]" multiple>
                </div>
                <div class="form-group col-sm-3">
                    <label for="description">Expense Description</label>
                    <textarea type="text" class="form-control" id="description" name="description" rows="1" required></textarea>
                </div>
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-success" value="+ Add">
            </div>
        </form>
    @endif

    <form id="updateForm" method="POST" action="" enctype="multipart/form-data" style="display: none;">
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

        <div class="row" >
            <div class="form-group col-sm-2">
                <label for="date">Expense Date</label>
                <input type="date" class="form-control" id="updateDate" name="date" required>
            </div>
            <div class="form-group col-sm-3">
                <label for="category">Expense Category</label>
                <select class="form-control" id="updateCategory" name="category" required>
                    @foreach ($expenseCategories as $category)
                        <option value="{{ $category->id }}">
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-sm-2">
                <label for="amount">Expense Amount</label>
                <input type="number" class="form-control" id="updateAmount" name="amount" required>
            </div>
            <div class="form-group col-sm-2">
                <label for="bill">Expense Bill</label>
                <input type="file" class="form-control" id="updateBill" name="bill[]" multiple>
            </div>
            <div class="form-group col-sm-3">
                <label for="description">Expense Description</label>
                <textarea type="text" class="form-control" id="updateDescription" name="description" rows="1" required></textarea>
            </div>
        </div>
        <div class="form-group">
            @if($voucher->status === 0)
                <input type="submit" class="btn btn-primary" value="Update">
                <input type="button" id="deleteExpenseBtn" class="btn btn-danger ml-3" value="Delete">
            @endif
        </div>
    </form>

    <br><br>

    <div class="table-responsive">
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Date</th>
                    <th scope="col">Category</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Bill</th>
                    <th scope="col">Description</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($expenses as $expense)
                    <tr @if($voucher->status === 0)class="editExpenseBtn"@endif>
                        <td hidden class="data">{{ $expense->id }}</td>
                        <td class="data">{{ $expense->date->format('d-M-Y') }}</td>
                        <td hidden class="data">{{ $expense->category_id }}</td>
                        <td class="data">{{ App\Models\ExpenseCategory::where('id', $expense->category_id)->first()->name }}</td>
                        <td class="data">
                            <span class="badge badge-primary px-2 py-2" style="font-size: 1.1rem;">₹ {{ $expense->amount }}</span>
                        </td>
                        <td>
                            <a href="#">click to download</a>
                        </td>
                        <td class="data">{{ $expense->description }}</td>
                    </tr>
                    @foreach ($expense->bills as $bill)
                        <span class="billurl {{ $expense->id }}" hidden>
                            {{ asset('storage/bill/' . $bill->file_name) }}
                        </span>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
    <br>


    <div class="form-group mt-3 mx-auto">
        @if($voucher->status === 0)
            <input type="button" id="applyForApprovalBtn" class="btn btn-primary" value="Apply For Approval">
            <a class="btn btn-danger ml-3" href="{{ route('vouchers.index') }}">Cancel</a>
        @endif
    </div>

</div>

<input type="hidden" id="voucherId" value="{{ $voucher->id }}">
<input type="hidden" id="url" value="{{ route('vouchers.askForApproval') }}">
<input type="hidden" id="deleteExpenseUrl" value="{{ route('vouchers.destroyExpense') }}">
@stop

@section('js')
    <script src="{{ asset('js/downloadfiles.js') }}"></script>
    <script src="{{ asset('js/approval.js') }}"></script>
    <script src="{{ asset('js/s2.js') }}"></script>
@stop
