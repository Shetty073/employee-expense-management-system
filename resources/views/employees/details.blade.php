@extends('adminlte::page')

@section('title', 'View Voucher')

@section('content_header')
    <h1>View Voucher</h1>
@stop

@section('content')
<div class="card px-3 py-1">

    <form>
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
            <div class="form-group col-sm-6">
                <label for="voucherdate">Employee Name</label>
                <input type="text" class="form-control" id="employeename" name="employeename" value="{{ $voucher->employee()->first()->name }}" disabled>
            </div>
            <div class="form-group col-sm-6">
                <label for="voucherdate">Voucher Date</label>
                <input type="date" class="form-control" id="voucherdate" name="voucherdate" value="{{ Carbon\Carbon::now()->format('Y-m-d') }}"
                value="@if(isset($voucher)){{ $voucher->voucherdate }}@else{{ old('voucherdate') }}@endif" required disabled>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-4">
                <label for="job">Selected Job(s)</label>
                <select class="form-control js-example-basic-multiple" id="job" name="job[]" multiple="multiple" disabled>
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

    <div class="table-responsive">
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Date</th>
                    <th scope="col">Category</th>
                    <th scope="col">Description</th>
                    <th scope="col">Bill</th>
                    <th scope="col">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($expenses as $expense)
                    <tr>
                        <td hidden class="data">{{ $expense->id }}</td>
                        <td class="data">{{ $expense->date->format('d-m-Y') }}</td>
                        <td hidden class="data">{{ $expense->category_id }}</td>
                        <td class="data">{{ App\Models\ExpenseCategory::where('id', $expense->category_id)->first()->name }}</td>
                        <td class="data">{{ $expense->description }}</td>
                        <td>
                            @if($expense->bill !== null)
                                <a class="badge badge-secondary" href="{{ asset('storage/bill/' . $expense->bill) }}">VIEW</a>
                            @else
                                <span class="badge badge-danger">Bill Not Provided</span>
                            @endif
                        </td>
                        <td class="data">
                            <span class="badge badge-primary px-2 py-2">₹ {{ $expense->amount }}</span>
                        </td>
                    </tr>
                @endforeach
                    <?php
                        $total_amt = 0.0;
                        foreach ($expenses as $exp) {
                            $total_amt += $exp->amount;
                        }
                    ?>
                    <tr class="table-warning" style="font-size: 1.5rem;">
                        <td colspan="1" scope="row" style="font-weight: 800;">Grand Total :-</td>
                        <td colspan="2" style="font-weight: 800;">
                            ₹ {{ $total_amt }}
                        </td>
                        <td colspan="2"></td>
                    </tr>
                    <tr class="table-warning" style="font-size: 1.5rem;">
                        <td colspan="1" scope="row" style="font-weight: 800;">Wallet Balance :-</td>
                        <td colspan="2" style="font-weight: 800;">
                            ₹ {{ $voucher->employee()->first()->wallet_balance }}
                        </td>
                        <td colspan="2"></td>
                    </tr>
            </tbody>
        </table>
    </div>
    <br>

    <form>
        <div class="row">
            <div class="col-sm-4">
                <label for="totalAmountPaid">Amount Paid:</label>
                <input type="number" id="totalAmountPaid" class="form-control" value="{{ $total_amt }}">
            </div>
            <div class="col-sm-4">
                <label for="paymentMode">Payment Mode:</label>
                <select class="form-control" id="paymentMode">
                    <option value="0">{{ App\Accunity\Utils::PAYMENT_MODES[0] }}</option>
                    <option value="1">{{ App\Accunity\Utils::PAYMENT_MODES[1] }}</option>
                    <option value="2">{{ App\Accunity\Utils::PAYMENT_MODES[2] }}</option>
                    <option value="3">{{ App\Accunity\Utils::PAYMENT_MODES[3] }}</option>
                </select>
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
                <input type="text" class="form-control" id="remark" name="remark" required>
            </div>
        </div>
    </form>

    <div class="form-group mt-3 mx-auto">
        @if($voucher->status === 0)
            <div class="alert alert-primary" role="alert">
                Approval is not yet requested by the author of this voucher!
            </div>
        @elseif($voucher->status === 1)
            <button class="btn btn-success" id="approveVoucherBtn">
                <i class="fas fa-check"></i> Approve
            </button>
            <button class="btn btn-danger" id="rejectVoucherBtn">
                <i class="fas fa-times"></i> Reject
            </button>
        @elseif($voucher->status === 2)
            <div class="alert alert-success" role="alert">
                This voucher has been approved!
            </div>
        @elseif($voucher->status === 3)
            <div class="alert alert-danger" role="alert">
                This voucher has been rejected!
            </div>
        @endif
    </div>

</div>

<input type="hidden" id="voucherId" value="{{ $voucher->id }}">
<input type="hidden" id="url" value="{{ route('vouchers.voucherApproveReject') }}">
@stop

@section('js')
    <script src="{{ asset('js/adminVoucherApproval.js') }}"></script>
    <script src="{{ asset('js/s2.js') }}"></script>
@stop
