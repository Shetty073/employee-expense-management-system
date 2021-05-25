<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Job;
use App\Models\Payment;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function approvalRequests()
    {
        $vouchers = Voucher::where('status', 1)->get();

        return view('employees.approval', compact('vouchers'));
    }

    public function approvedVouchers()
    {
        $vouchers = Voucher::where('status', 2)->get();

        return view('employees.approved', compact('vouchers'));
    }

    public function rejectedVouchers()
    {
        $vouchers = Voucher::where('status', 3)->get();

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

    public function voucherApproveReject(Request $request)
    {
        // this will respond to fetch API request and based on data
        // it will set the status of voucher to 2 (approved) or 3 (rejected)
        $voucherId = json_decode($request->getContent(), true)['voucher_id'];
        $status = json_decode($request->getContent(), true)['status'];
        $expense_remarks = json_decode($request->getContent(), true)['expense_remarks'];
        // $expense_amounts = json_decode($request->getContent(), true)['expense_amounts'];

        $voucher = Voucher::findorfail($voucherId);
        $voucher->update([
            'status' => $status,
        ]);

        $employee = $voucher->employee()->first();

        if($status == 2)
        {
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
            ]);

            if ($request->input('remark') == null) {
                $payment->remark = 'Voucher Accepted';
            } else {
                $payment->remark = 'Voucher Accepted - ' . $remark;
            }

            $payment->employee()->associate($employee);
            $payment->save();

            $voucher->approved_amount = $amount;
            $voucher->save();
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
        // if(count($expense_amounts) > 0) {
        //     foreach ($expense_amounts as $id => $amount) {
        //         $expense = Expense::where('id', $id)->first();
        //         $expense->update([
        //             'amount' => $amount,
        //         ]);
        //     }
        // }

        // Note: approval_date can be treated as rejection_date for rejected vouchers
        $voucher->approval_date = Carbon::now();
        $voucher->save();

        return response()->json([
            'process' => 'success',
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vouchers = Voucher::where('employee_id', auth()->user()->employee->id)->get();

        return view('vouchers.index', compact('vouchers'));
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $voucher->update([
            'status' => 1,
        ]);

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
