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
                <input type="date" class="form-control" id="voucherdate" name="voucherdate"
                value="@if(isset($voucher)){{ $voucher->date->format('Y-m-d') }}@else{{ old('voucherdate') }}@endif" required disabled>
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
                    <th scope="col">Proposed Amount</th>
                    <th scope="col">Approved Amount</th>
                    <th scope="col">Description</th>
                    <th scope="col">Remark</th>
                    <th scope="col">Bill</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($expenses as $expense)
                    <tr>
                        <td class="data">{{ $expense->date->format('d-M-Y') }}</td>
                        <td hidden class="data">{{ $expense->category_id }}</td>
                        <td class="data">{{ App\Models\ExpenseCategory::where('id', $expense->category_id)->first()->name }}</td>
                        <td>
                            <span class="badge badge-primary px-2 py-2">
                                ₹ {{ $expense->amount }}
                            </span>
                        </td>
                        <td class="data">
                            @if($voucher->status === 1)
                                <span class="badge badge-primary px-2 py-2">
                                    ₹ <input type="number" class="expenseamount" id="{{ $expense->id }}" value="{{ $expense->amount }}">
                                </span>
                            @else
                                <span class="badge badge-primary px-2 py-2">
                                    ₹ {{ $expense->approved_amount }}
                                </span>
                            @endif
                        </td>
                        <td class="data">{{ $expense->description }}</td>
                        <td class="data">
                            @if($voucher->status === 1)
                                <input type="text" class="expenseremark" id="{{ $expense->id }}" value="{{ $expense->remark }}">
                            @else
                                {{ $expense->remark }}
                            @endif
                        </td>
                        <td>
                            @if(count($expense->bills) > 0)
                                <button class="btn btn-primary downloadExpenseBillsBtn" id="{{ $expense->id }}">Download</button>
                            @else
                                <span class="badge badge-danger">Bill Not Provided</span>
                            @endif
                        </td>
                    </tr>
                    @foreach ($expense->bills as $bill)
                        <span class="billurl {{ $expense->id }}" hidden>
                            {{ asset('storage/bill/' . $bill->file_name) }}
                        </span>
                    @endforeach
                @endforeach
                    <?php
                        $total_amt = 0.0;
                        foreach ($expenses as $exp) {
                            $total_amt += $exp->amount;
                        }
                    ?>
                    <tr class="table-warning" style="font-size: 1.2rem;">
                        <td colspan="2" scope="row" style="font-weight: 800;">Total:-</td>
                        <td style="font-weight: 800;">
                            ₹ {{ $total_amt }}
                        </td>
                        <td style="font-weight: 800;">
                            @if ($voucher->approved_amount)
                                ₹ {{ $voucher->approved_amount }}
                            @else
                                Not Yet Approved
                            @endif
                        </td>
                        <td colspan="2"></td>
                        <td>
                            <button class="btn btn-primary" id="downloadAllBillsBtn">
                                Download All Bills
                            </button>
                        </td>
                    </tr>
                    <tr class="table-warning" style="font-size: 1.2rem;">
                        <td colspan="1" scope="row" style="font-weight: 800;">Wallet Balance:-</td>
                        <td colspan="2" style="font-weight: 800;">
                            ₹ {{ $voucher->employee()->first()->wallet_balance }}
                        </td>
                        <td colspan="4"></td>
                    </tr>
            </tbody>
        </table>
    </div>
    <br>

    @foreach ($voucher->sitecompletiondocs as $doc)
        <span class="site_completion_doc_url" hidden>
            {{ asset('storage/site_completion_doc/' . $doc->file_name) }}
        </span>
    @endforeach
    @foreach ($voucher->receiveddocs as $doc)
        <span class="received_doc_url" hidden>
            {{ asset('storage/received_doc/' . $doc->file_name) }}
        </span>
    @endforeach
    @foreach ($voucher->returnablelistdocs as $doc)
        <span class="returnable_list_doc_url" hidden>
            {{ asset('storage/returnable_list_doc/' . $doc->file_name) }}
        </span>
    @endforeach
    @foreach ($voucher->submitteddocs as $doc)
        <span class="submitted_doc_url" hidden>
            {{ asset('storage/submitted_doc/' . $doc->file_name) }}
        </span>
    @endforeach

    <div class="row">
        <div class="form-group col-sm-3">
            <label for="siteCompletionDocBtn">Site Completion Docs: </label>
            @if(count($voucher->sitecompletiondocs) > 0)
                <button class="btn btn-secondary" id="siteCompletionDocBtn">Download Files</button>
            @else
                <span class="badge badge-secondary">No Files</span>
            @endif
        </div>
        <div class="form-group col-sm-3">
            <label for="receivedDocsBtn">Received Docs: </label>
            @if(count($voucher->receiveddocs) > 0)
                <button class="btn btn-secondary" id="receivedDocsBtn">Download Files</button>
            @else
                <span class="badge badge-secondary">No Files</span>
            @endif
        </div>
        <div class="form-group col-sm-3">
            <label for="returnableListBtn">Returnable List: </label>
            @if(count($voucher->returnablelistdocs) > 0)
                <button class="btn btn-secondary" id="returnableListBtn">Download Files</button>
            @else
                <span class="badge badge-secondary">No Files</span>
            @endif
        </div>
        <div class="form-group col-sm-3">
            <label for="submittedDocsBtn">Submitted Docs: </label>
            @if(count($voucher->submitteddocs) > 0)
                <button class="btn btn-secondary" id="submittedDocsBtn">Download Files</button>
            @else
                <span class="badge badge-secondary">No Files</span>
            @endif
        </div>
    </div>
    <br>

    @if($voucher->status === 1)
        <div class="row">
            <div class="col-sm-4">
                <label for="special_remark">Special Remark:</label>
                <input type="text" id="special_remark" class="form-control">
            </div>
        </div>
        <br>
        <form>
            <div class="row">
                <div class="col-sm-4">
                    <label for="totalAmountPaid">Total Approved Amount:</label>
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
    @endif

    @if($voucher->status > 1)
        <?php
            $perCategoryTotal = array();
            foreach ($expenseCategories as $category) {
                $categoryWiseTotal = 0;
                foreach ($expenses as $expense) {
                    if ($category->id === $expense->expensecategory->id) {
                        $categoryWiseTotal += $expense->approved_amount;
                    }
                }
                $perCategoryTotal[$category->name] = $categoryWiseTotal;
            }
        ?>

        <h6 style="font-weight: 700;">Categorywise list of approved total amount:</h6>
        <div class="row">
            <div class="col-sm-3">
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                            @foreach ($perCategoryTotal as $category => $total)
                                <tr scope="row" style="font-size: 1.2rem;">
                                    <td style="font-weight: 700;">{{ $category }}</td>
                                    <td>₹ {{ $total }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <br>

        <div class="row">
            <div class="col-sm-3">
                <b>Special Remark: </b> {{ $voucher->special_remark }}
            </div>
        </div>

    @endif

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
                <a href="{{ route('employees.voucherDetailsPdf', ['id' => $voucher->id]) }}"
                    class="btn btn-secondary ml-2">
                    <i class="fas fa-print"></i> Print Report
                </a>
            </div>
        @elseif($voucher->status === 3)
            <div class="alert alert-danger" role="alert">
                This voucher has been rejected!
                <a href="{{ route('employees.voucherDetailsPdf', ['id' => $voucher->id]) }}"
                    class="btn btn-secondary ml-2">
                    <i class="fas fa-print"></i> Download Report
                </a>
            </div>
        @endif
    </div>

</div>

<input type="hidden" id="voucherId" value="{{ $voucher->id }}">
<input type="hidden" id="url" value="{{ route('vouchers.voucherApproveReject') }}">
@stop

@section('js')
    <script src="{{ asset('js/downloadfiles.js') }}"></script>
    <script src="{{ asset('js/adminVoucherApproval.js') }}"></script>
    <script src="{{ asset('js/s2.js') }}"></script>
@stop
