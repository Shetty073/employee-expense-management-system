<?php

namespace App\Http\Controllers;

use App\Accunity\Utils;
use App\Models\Bill;
use App\Models\Employee;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Job;
use App\Models\Payment;
use App\Models\ReceivedDoc;
use App\Models\ReturnableListDoc;
use App\Models\SiteCompletionDoc;
use App\Models\SubmittedDoc;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;

class VoucherController extends Controller
{
    public function approvalRequests()
    {
        if(auth()->user()->is_admin) {
            $vouchers = Voucher::where('status', 1)->get();
        } else {
            $employee = Employee::where('user_id', auth()->user()->id)->first();
            $vouchers = Voucher::where('status', 1)->where('employee_id', $employee->id)->get();
        }

        return view('employees.approval', compact('vouchers'));
    }

    public function approvedVouchers()
    {
        if(auth()->user()->is_admin) {
            $vouchers = Voucher::where('status', 2)->get()->sortByDesc('approval_date');
        } else {
            $employee = Employee::where('user_id', auth()->user()->id)->first();
            $vouchers = Voucher::where('status', 2)->where('employee_id', $employee->id)->get()->sortByDesc('approval_date');
        }

        return view('employees.approved', compact('vouchers'));
    }

    public function rejectedVouchers()
    {
        if(auth()->user()->is_admin) {
            $vouchers = Voucher::where('status', 3)->get()->sortByDesc('approval_date');
        } else {
            $employee = Employee::where('user_id', auth()->user()->id)->first();
            $vouchers = Voucher::where('status', 3)->where('employee_id', $employee->id)->get()->sortByDesc('approval_date');
        }

        return view('employees.rejected', compact('vouchers'));
    }

    public function voucherDetails($id)
    {
        $voucher = Voucher::findorfail($id);
        $expenses = $voucher->expenses()->get();
        $expenseCategories = ExpenseCategory::all();
        $jobs = Job::all();

        return view('employees.details', compact('voucher', 'expenseCategories', 'jobs', 'expenses'));
    }

    public function generateHtmlContent($voucher)
    {
        $expenses = $voucher->expenses()->get();
        $expenseCategories = ExpenseCategory::all();
        $jobs = Job::all();
        $voucherjobs = $voucher->jobs()->get();

        $content = '<!doctype html>
            <html lang="en">
            <head>
                <!-- Required meta tags -->
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

                <!-- Latest compiled and minified CSS -->
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

                <!-- Optional theme -->
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

                <title>Voucher Report</title>
                <style>
                    body {
                        font-family: DejaVu Sans;
                        font-size: 10px;
                    }

                    .thin-col {
                        max-width: 15px !important;
                    }
                </style>
            </head>
            <body>';

        $content .= '
            <div class="row">
                <div class="text-center">
                    <img src="' . asset('logo/logo.jpg') . '" />
                </div>
            </div>
            <div class="row">
                <div class="col-xs-3"><b>Voucher Number: ' . $voucher->number . '</b></div>
            </div>';

        $content .= '
            <div class="row">
                <div class="col-xs-3"><b>Employee Name:</b> ' . $voucher->employee()->first()->name . '</div>
                <div class="col-xs-3"><b>Employee Number:</b> ' . $voucher->employee()->first()->number . '</div>
            </div>';

        $content .= '
            <div class="row">
                <div class="col-xs-3"><b>Voucher Date:</b> ' . $voucher->date->format('d-M-Y') . '</div>
                <div class="col-xs-3"><b>Approval Date:</b> ' . $voucher->approval_date->format('d-M-Y') . '</div>
            </div>';

        $content .= '
        <div class="row">
            <div class="col-xs-6"><b>Voucher Jobs:</b>
        ';

        foreach ($voucherjobs as $job) {
            # code...
            $content .= $job->number . ', ';
        }

        $content .= '</div>
            </div><br>';

        $content .= '<div class="table-responsive">
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Date</th>
                    <th scope="col">Category</th>
                    <th scope="col">Proposed Amt.</th>
                    <th scope="col">Approved Amt.</th>
                    <th scope="col">Description</th>
                    <th scope="col">Remark</th>
                    <th scope="col">Bill</th>
                </tr>
            </thead>
            <tbody>';
                foreach ($expenses as $expense) {
                    $content .= '<tr>
                        <td>' . $expense->date->format('d-M-Y') . '</td>
                        <td>' . ExpenseCategory::where('id', $expense->category_id)->first()->name . '</td>
                        <td>
                            ₹ ' . $expense->amount . '
                        </td>
                        <td>';
                        $content .= '₹ ' . $expense->approved_amount;
                        $content .= '</td>
                        <td>' . $expense->description . '</td>
                        <td>' .
                            $expense->remark
                        . '</td>
                        <td>';
                        if(count($expense->bills) > 0) {
                            $content .= count($expense->bills) . ' bills </td>';
                        } else {
                            $content .= 'No bill </td>';
                        }
                    $content .= '</tr>';
                }

                $total_amt = 0.0;
                foreach ($expenses as $exp) {
                    $total_amt += $exp->amount;
                }

                if($voucher->status === 2) {
                    $content .= '<tr class="table-warning">
                        <td colspan="2" scope="row"><b>Total:-</b></td>
                        <td>
                            <b>₹ ' . $total_amt .
                        '</b></td>
                        <td>
                            <b>₹ ' . $voucher->approved_amount .
                        '</b></td>
                        <td colspan="3"></td>
                    </tr>';
                }

                $content .= '
                    <tr class="table-warning">
                        <td colspan="1" scope="row"><b>Wallet Balance:-</b></td>
                        <td colspan="2">
                            <b>₹ ' . $voucher->employee()->first()->wallet_balance .
                        '</b></td>
                        <td colspan="4"></td>
                    </tr>
                </tbody>
            </table>
        </div>';

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

        $content .= '<h6 style="font-weight: 700;">Categorywise list of approved total amount:</h6>
        <div class="row">
            <div class="col-sm-3">
                <div class="table-responsive">
                    <table class="table">
                        <tbody>';
                            foreach ($perCategoryTotal as $category => $total) {
                                $content .= '<tr scope="row" style="font-size: 1.2rem;">
                                    <td style="font-weight: 700;">' . $category . '</td>
                                    <td>₹ ' . $total . '</td>
                                </tr>';
                            }
        $content .= '</tbody>
                    </table>
                </div>
            </div>
        </div>';

        $content .= '<div class="row">
            <div class="col-sm-3">
                Site Completion: ';

        if (count($voucher->sitecompletiondocs) > 0) {
            $content .= '&check;';
        }

        $content .= '
            </div>
            <div class="col-sm-3">
                Received Docs: ';

        if (count($voucher->receiveddocs) > 0) {
            $content .= '&check;';
        }

        $content .= '</div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    Returnable List: ';

        if (count($voucher->returnablelistdocs) > 0) {
            $content .= '&check;';
        }

        $content .= '</div>
            <div class="col-sm-3">
                Submitted Docs: ';

        if (count($voucher->submitteddocs) > 0) {
            $content .= '&check;';
        }

        $content .= '</div>
            </div><br>';

        $content .= '<div class="table-responsive">
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col thin-col" colspan="4">Special Remark</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="4">' . $voucher->special_remark .'</td>
                </tr>
            </tbody>
        </table>
        </div>';

        $content .= '<div class="table-responsive">
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col thin-col">Amount Giver/Receiver</th>
                    <th scope="col">Name</th>
                    <th scope="col">Date & Time</th>
                    <th scope="col">Sign</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Pay To Company</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Pay To Employee</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        </div><br>';

        $content .= '<div class="table-responsive">
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col thin-col">Project Allowance</th>
                    <th scope="col">Number Of Days</th>
                    <th scope="col">Approved Days</th>
                    <th scope="col">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        </div>';

        $content .= '
            <!-- Latest compiled and minified JavaScript -->
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
            </body>
            </html>';

        return $content;
    }

    public function voucherDetailsPdf($id)
    {
        $voucher = Voucher::findorfail($id);

        $content = $this->generateHtmlContent($voucher);
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($content);

        $fileName = 'voucher_report_' . $voucher->approval_date->format('d_m_Y') . '.pdf';
        return $pdf->download($fileName);
    }

    public function voucherApproveReject(Request $request)
    {
        // this will respond to fetch API request and based on data
        // it will set the status of voucher to 2 (approved) or 3 (rejected)
        $voucherId = json_decode($request->getContent(), true)['voucher_id'];
        $status = json_decode($request->getContent(), true)['status'];
        $special_remark = json_decode($request->getContent(), true)['special_remark'];
        $expense_remarks = json_decode($request->getContent(), true)['expense_remarks'];
        $expense_amounts = json_decode($request->getContent(), true)['expense_amounts'];

        $voucher = Voucher::findorfail($voucherId);
        $voucher->update([
            'status' => $status,
            'special_remark' => $special_remark,
        ]);

        $employee = $voucher->employee()->first();

        $emailsubject = 'Your voucher has been rejected';
        $emailmessage = 'We are sorry to inform you that your voucher numbered ' . $voucher->number . ' has been rejected.';

        if($status == 2) {
            $date = json_decode($request->getContent(), true)['date'];
            $payment_mode = json_decode($request->getContent(), true)['payment_mode'];
            $amount = json_decode($request->getContent(), true)['amount'];
            $remark = json_decode($request->getContent(), true)['remark'];

            // voucher is approved, reflect this in employee's wallet balance
            $wallet_balance = $employee->wallet_balance;
            $wallet_balance = $wallet_balance - floatval($amount);
            $employee->wallet_balance = $wallet_balance;
            $employee->save();

            $payment = Payment::create([
                'date' => $date,
                'payment_mode' => $payment_mode,
                'amount' => $amount,
                'remark' => 'Voucher Accepted - ' . $special_remark,
            ]);

            $payment->employee()->associate($employee);
            $payment->save();

            $voucher->approved_amount = $amount;
            $voucher->addprovedBy()->associate(auth()->user());
            $voucher->save();

            $emailsubject = 'Your voucher has been accepted';
            $emailmessage = 'We are happy to inform you that your voucher numbered ' . $voucher->number . ' has been accepted.';
        }

        // update the remarks for each expense if any
        if(count($expense_remarks) > 0) {
            foreach ($expense_remarks as $id => $remark) {
                $expense = Expense::where('id', $id)->first();
                $expense->update([
                    'remark' => $remark,
                ]);
            }
        }

        // update the amount for each expense in case its changed
        if(count($expense_amounts) > 0) {
            foreach ($expense_amounts as $id => $amount) {
                $expense = Expense::where('id', $id)->first();
                $expense->update([
                    'approved_amount' => $amount,
                ]);
            }
        }

        // Note: approval_date can be treated as rejection_date for rejected vouchers
        $voucher->approval_date = Carbon::now();
        $voucher->save();

        // Send email to employee regarding voucher status update
        $data = [
            'employee' => $employee,
            'voucher' => $voucher,
            'emailmessage' => $emailmessage,
        ];
        $emails = [$employee->email, 'anujdxb@gmail.com', 'accounts@litmusinternational.com'];
        Mail::send('emails.voucherstatus', $data, function($message) use ($employee, $emailsubject, $emails) {
            $message->to($emails, $employee->name)->subject($emailsubject);
            $message->from(Utils::SENDER_EMAIL, Utils::SENDER_NAME);
        });

        return response()->json([
            'process' => 'success',
        ]);
    }

    public function voucherSaveDraft(Request $request)
    {
        // this will respond to fetch API request and based on data
        // it will set the status of voucher to 2 (approved) or 3 (rejected)
        $voucherId = json_decode($request->getContent(), true)['voucher_id'];
        $special_remark = json_decode($request->getContent(), true)['special_remark'];
        $expense_remarks = json_decode($request->getContent(), true)['expense_remarks'];
        $expense_amounts = json_decode($request->getContent(), true)['expense_amounts'];

        $voucher = Voucher::findorfail($voucherId);
        $voucher->update([
            'special_remark' => $special_remark,
        ]);

        // update the remarks for each expense if any
        if(count($expense_remarks) > 0) {
            foreach ($expense_remarks as $id => $remark) {
                $expense = Expense::where('id', $id)->first();
                $expense->update([
                    'remark' => $remark,
                ]);
            }
        }

        // update the amount for each expense in case its changed
        if(count($expense_amounts) > 0) {
            foreach ($expense_amounts as $id => $amount) {
                $expense = Expense::where('id', $id)->first();
                $expense->update([
                    'approved_amount' => $amount,
                ]);
            }
        }

        return response()->json([
            'process' => 'success',
        ]);
    }

    public function addExpenseBills(Request $request)
    {
        $expenseId = $request->input('expenseid');
        $expense = Expense::findorfail($expenseId);

        if ($request->hasFile('bill')) {
            $bills = $request->file('bill');

            // Save the bill files
            foreach ($bills as $billfile) {
                $bill = Bill::create([
                    'file_name' => '',
                ]);
                $fileName = $billfile->getClientOriginalName();
                $fileExtension = $billfile->getClientOriginalExtension();
                $fileName = chop($fileName, $fileExtension);
                $fileNameToStore = $fileName . '_' . $bill->id . '_' . $expense->id . '_' . time() . '.' . $fileExtension;
                $path = $billfile->storeAs('public/bill', $fileNameToStore);

                $bill->file_name = $fileNameToStore;
                $bill->expense()->associate($expense);
                $bill->save();
            }

        }

        return back();
    }

    public function attachAdditionalFiles(Request $request, $id)
    {
        $voucher = Voucher::findorfail($id);

        if ($request->hasFile('site_completion')) {
            $site_completion_docs = $request->file('site_completion');

            // Save the site completion doc files
            foreach ($site_completion_docs as $doc) {
                $site_completion_doc = SiteCompletionDoc::create([
                    'file_name' => '',
                ]);
                $fileName = $doc->getClientOriginalName();
                $fileExtension = $doc->getClientOriginalExtension();
                $fileName = chop($fileName, $fileExtension);
                $fileNameToStore = $fileName . '_' . $site_completion_doc->id . '_' . $voucher->id . '_' . time() . '.' . $fileExtension;
                $path = $doc->storeAs('public/site_completion_doc', $fileNameToStore);

                $site_completion_doc->file_name = $fileNameToStore;
                $site_completion_doc->voucher()->associate($voucher);
                $site_completion_doc->save();
            }

        }

        if ($request->hasFile('received_docs')) {
            $received_docs = $request->file('received_docs');

            // Save the received doc files
            foreach ($received_docs as $doc) {
                $received_doc = ReceivedDoc::create([
                    'file_name' => '',
                ]);
                $fileName = $doc->getClientOriginalName();
                $fileExtension = $doc->getClientOriginalExtension();
                $fileName = chop($fileName, $fileExtension);
                $fileNameToStore = $fileName . '_' . $received_doc->id . '_' . $voucher->id . '_' . time() . '.' . $fileExtension;
                $path = $doc->storeAs('public/received_doc', $fileNameToStore);

                $received_doc->file_name = $fileNameToStore;
                $received_doc->voucher()->associate($voucher);
                $received_doc->save();
            }

        }

        if ($request->hasFile('returnable_list')) {
            $returnable_list_docs = $request->file('returnable_list');

            // Save the returnable list doc files
            foreach ($returnable_list_docs as $doc) {
                $returnable_list_doc = ReturnableListDoc::create([
                    'file_name' => '',
                ]);
                $fileName = $doc->getClientOriginalName();
                $fileExtension = $doc->getClientOriginalExtension();
                $fileName = chop($fileName, $fileExtension);
                $fileNameToStore = $fileName . '_' . $returnable_list_doc->id . '_' . $voucher->id . '_' . time() . '.' . $fileExtension;
                $path = $doc->storeAs('public/returnable_list_doc', $fileNameToStore);

                $returnable_list_doc->file_name = $fileNameToStore;
                $returnable_list_doc->voucher()->associate($voucher);
                $returnable_list_doc->save();
            }

        }

        if ($request->hasFile('submitted_docs')) {
            $submitted_docs = $request->file('submitted_docs');

            // Save the submitted doc files
            foreach ($submitted_docs as $doc) {
                $submitted_doc = SubmittedDoc::create([
                    'file_name' => '',
                ]);
                $fileName = $doc->getClientOriginalName();
                $fileExtension = $doc->getClientOriginalExtension();
                $fileName = chop($fileName, $fileExtension);
                $fileNameToStore = $fileName . '_' . $submitted_doc->id . '_' . $voucher->id . '_' . time() . '.' . $fileExtension;
                $path = $doc->storeAs('public/submitted_doc', $fileNameToStore);

                $submitted_doc->file_name = $fileNameToStore;
                $submitted_doc->voucher()->associate($voucher);
                $submitted_doc->save();
            }

        }

        return back();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vouchers = Voucher::where('employee_id', auth()->user()->employee->id)->where('status', 1)->get();

        return view('vouchers.index', compact('vouchers'));
    }

    public function draft()
    {
        $vouchers = Voucher::where('employee_id', auth()->user()->employee->id)->where('status', 0)->get();

        return view('vouchers.draft', compact('vouchers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $jobs = Job::all();

        return view('vouchers.create', compact('jobs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->flash();

        $this->validate($request, [
            'job' =>'required',
        ]);

        $voucher = Voucher::create([
            'date' => Carbon::now(),
        ]);

        foreach ($request->input('job') as $key => $value) {
            $job = Job::findorfail($value);
            $voucher->jobs()->attach($job);
            $voucher->save();
        }

        $employee = auth()->user()->employee;
        $voucher->employee()->associate($employee);
        $voucher->save();

        return redirect(route('vouchers.edit', ['id' => $voucher->id]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $voucher = Voucher::findorfail($id);
        $expenses = $voucher->expenses()->get();
        $expenseCategories = ExpenseCategory::all();
        $jobs = Job::all();

        return view('vouchers.edit', compact('voucher', 'expenseCategories', 'jobs', 'expenses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'job' =>'required',
        ]);

        $voucher = Voucher::findorfail($id);

        $voucher->jobs()->detach();

        foreach ($request->input('job') as $key => $value) {
            $job = Job::findorfail($value);
            $voucher->jobs()->attach($job);
            $voucher->save();
        }

        return redirect(route('vouchers.edit', ['id' => $voucher->id]));
    }

    public function createExpense(Request $request, $id)
    {
        $request->flash();

        $this->validate($request, [
            'date' => 'required',
            'category' => 'required',
            'description' => 'required',
            'amount' => 'required',
        ]);

        $voucher = Voucher::findorfail($id);
        $expensecategory = ExpenseCategory::findorfail($request->input('category'));


        $expense = Expense::create([
            'date' => $request->input('date'),
            'description' => $request->input('description'),
            'amount' => $request->input('amount'),
        ]);

        if ($request->hasFile('bill')) {
            $bills = $request->file('bill');

            // Save the bill files
            foreach ($bills as $billfile) {
                $bill = Bill::create([
                    'file_name' => '',
                ]);
                $fileName = $billfile->getClientOriginalName();
                $fileExtension = $billfile->getClientOriginalExtension();
                $fileName = chop($fileName, $fileExtension);
                $fileNameToStore = $fileName . '_' . $bill->id . '_' . $expense->id . '_' . time() . '.' . $fileExtension;
                $path = $billfile->storeAs('public/bill', $fileNameToStore);

                $bill->file_name = $fileNameToStore;
                $bill->expense()->associate($expense);
                $bill->save();
            }

        }

        $expense->voucher()->associate($voucher);
        $expense->expensecategory()->associate($expensecategory);
        $expense->save();

        return redirect(route('vouchers.edit', ['id' => $voucher->id]));
    }

    public function updateExpense(Request $request, $id)
    {
        $this->validate($request, [
            'date' => 'required',
            'category' => 'required',
            'description' => 'required',
            'amount' => 'required',
        ]);

        $expense = Expense::findorfail($id);
        $expense->update([
            'date' => $request->input('date'),
            'description' => $request->input('description'),
            'amount' => $request->input('amount'),
        ]);

        if ($request->hasFile('bill')) {
            $bills = $request->file('bill');

            // Save the bill files
            foreach ($bills as $billfile) {
                $bill = Bill::create([
                    'file_name' => '',
                ]);
                $fileName = $billfile->getClientOriginalName();
                $fileExtension = $billfile->getClientOriginalExtension();
                $fileName = chop($fileName, $fileExtension);
                $fileNameToStore = $fileName . '_' . $bill->id . '_' . $expense->id . '_' . time() . '.' . $fileExtension;
                $path = $billfile->storeAs('public/bill', $fileNameToStore);

                // associate this newly uploaded bills to this expense
                $bill->file_name = $fileNameToStore;
                $bill->expense()->associate($expense);
                $bill->save();

                // delete old bills
                $old_bills = Bill::where('expense_id', $expense->id)->get();
                foreach ($old_bills as $old_bill) {
                    $file_path = public_path('storage/bill/' . $old_bill->file_name);
                    @unlink($file_path);

                    $old_bill->delete();
                }
            }

        }

        $expense->expensecategory()->disassociate();
        $expense->save();

        $expensecategory = ExpenseCategory::where('id', $request->input('category'))->first();
        $expense->expensecategory()->associate($expensecategory);
        $expense->save();

        $voucher = Voucher::where('id', $expense->voucher_id)->first();

        return redirect(route('vouchers.edit', ['id' => $voucher->id]));
    }

    public function destroyExpense(Request $request)
    {
        // This will respond to fetch API request
        $expenseId = json_decode($request->getContent(), true)['expense_id'];
        $expense = Expense::findorfail($expenseId);

        $old_bills = Bill::where('expense_id', $expense->id)->get();
        foreach ($old_bills as $old_bill) {
            $file_path = public_path('storage/bill/' . $old_bill->file_name);
            @unlink($file_path);

            $old_bill->delete();
        }
        $expense->delete();

        return response()->json([
            'process' => 'success',
        ]);
    }

    public function askForApproval(Request $request)
    {
        // This will respond to fetch API request
        // Set voucher status = 1 (Waiting For Approval)
        $voucherId = json_decode($request->getContent(), true)['voucher_id'];
        $voucher = Voucher::findorfail($voucherId);
        $employee = $voucher->employee;
        $voucher->update([
            'status' => 1,
        ]);

        // Send email to employee regarding voucher sent for approval
        $data = [
            'employee' => $employee,
            'voucher' => $voucher,
        ];
        $emails = [$employee->email, 'prahantk.litmus@gmail.com', 'anujdxb@gmail.com', 'accounts@litmusinternational.com'];
        Mail::send('emails.askforapproval', $data, function($message) use ($employee, $emails) {
            $message->to($emails, $employee->name)->subject('Voucher sent for approval');
            $message->from(Utils::SENDER_EMAIL, Utils::SENDER_NAME);
        });

        return response()->json([
            'process' => 'success',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $voucher = Voucher::findorfail($id);

        $expenses = $voucher->expenses()->get();
        foreach ($expenses as $expense) {
            $old_bills = Bill::where('expense_id', $expense->id)->get();
            foreach ($old_bills as $old_bill) {
                $file_path = public_path('storage/bill/' . $old_bill->file_name);
                @unlink($file_path);

                $old_bill->delete();
            }
            $expense->delete();
        }

        $voucher->delete();

        return redirect(route('vouchers.index'));
    }
}
