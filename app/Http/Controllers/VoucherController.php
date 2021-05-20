<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Job;
use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
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
        $expenseCategories = ExpenseCategory::all();
        $jobs = Job::all();

        return view('vouchers.edit', compact('voucher', 'expenseCategories', 'jobs'));
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
        // create expense
    }

    public function updateExpense(Request $request, $id)
    {
        // update expense
    }

    public function askForApproval(Request $request, $id)
    {
        // This will responf to fetch API request
        // Set voucher status = 1
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
        $voucher->delete();

        return view('vouchers.index');
    }
}
