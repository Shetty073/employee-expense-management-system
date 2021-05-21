<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Job;
use App\Models\Voucher;
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

    public function voucherApproveReject()
    {
        // this will respond to fetch API request and based on data
        // it will set the status of voucher to 2 (approved) or 3 (rejected)
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
            'voucherdate' => 'required',
        ]);

        $voucher = Voucher::create([
            'date' => $request->input('voucherdate'),
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
            'voucherdate' => 'required',
        ]);

        $voucher = Voucher::findorfail($id);

        $voucher->update([
            'date' => $request->input('voucherdate'),
        ]);

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
            // Save the bill file
            $fileName = $request->file('bill')->getClientOriginalName();
            $fileExtension = $request->file('bill')->getClientOriginalExtension();
            $fileName = chop($fileName, $fileExtension);
            $fileNameToStore = $fileName . '_' . $expense->id . '_' . time() . '.' . $fileExtension;
            $path = $request->file('bill')->storeAs('public/bill', $fileNameToStore);
            $expense->bill = $fileNameToStore;
            $expense->save();
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
            // Save the bill file
            $fileName = $request->file('bill')->getClientOriginalName();
            $fileExtension = $request->file('bill')->getClientOriginalExtension();
            $fileName = chop($fileName, $fileExtension);
            $fileNameToStore = $fileName . '_' . $expense->id . '_' . time() . '.' . $fileExtension;
            $path = $request->file('bill')->storeAs('public/bill', $fileNameToStore);
            $expense->bill = $fileNameToStore;
            $expense->save();
        }

        $expense->expensecategory()->disassociate();
        $expense->save();

        $expensecategory = ExpenseCategory::where('id', $request->input('category'))->first();
        $expense->expensecategory()->associate($expensecategory);
        $expense->save();

        $voucher = Voucher::where('id', $expense->voucher_id)->first();

        return redirect(route('vouchers.edit', ['id' => $voucher->id]));
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
            if ($expense->bill !== null) {
                // Delete old photo file
                $file_path = public_path('storage/bill/' . $expense->bill);
                @unlink($file_path);
            }
        }

        $voucher->delete();

        return redirect(route('vouchers.index'));
    }
}
