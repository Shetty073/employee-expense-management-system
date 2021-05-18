<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExpenseController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => '/'/*, 'middleware' => 'auth'*/], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
});

Route::group(['prefix' => '/jobs'/*, 'middleware' => 'auth'*/], function () {
    Route::get('/', [JobController::class, 'index'])->name('jobs.index');
    Route::get('/create', [JobController::class, 'create'])->name('jobs.create');
});

Route::group(['prefix' => '/expensecategories'/*, 'middleware' => 'auth'*/], function () {
    Route::get('/', [ExpenseCategoryController::class, 'index'])->name('expenses.index');
    Route::get('/create', [ExpenseCategoryController::class, 'create'])->name('expenses.create');
});

Route::group(['prefix' => '/employees'/*, 'middleware' => 'auth'*/], function () {
    Route::get('/', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/create', [EmployeeController::class, 'create'])->name('employees.create');
});

Route::group(['prefix' => '/expenses'/*, 'middleware' => 'auth'*/], function () {
    Route::get('/', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::get('/create', [ExpenseController::class, 'create'])->name('expenses.create');
});


