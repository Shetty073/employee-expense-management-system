<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $expensecategories = ExpenseCategory::all();

        return view('expensecategories.index', compact('expensecategories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('expensecategories.create');
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
            'name' => 'required',
        ]);

        ExpenseCategory::create([
            'name' => $request->input('name')
        ]);

        return redirect(route('expensecategories.index'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $expensecategory = ExpenseCategory::findorfail($id);

        return view('expensecategories.edit', compact('expensecategory'));
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
            'name' => 'required',
        ]);

        $expensecategory = ExpenseCategory::findorfail($id);

        $expensecategory->update([
            'name' => $request->input('name')
        ]);

        return redirect(route('expensecategories.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $expensecategory = ExpenseCategory::findorfail($id);
        $expensecategory->delete();

        return response()->json([
            'process' => 'success',
        ]);
    }
}
