<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\PaymentController;

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

// auth routes
Route::group(['prefix' => '/auth'], function () {
    Route::get('/index', [AuthController::class, 'index'])->name('auth.index')->middleware('auth');

    Route::get('/login', [AuthController::class, 'login'])->name('auth.login')->middleware('guest');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('auth.authenticate')->middleware('guest');

    Route::get('/register', [AuthController::class, 'register'])->name('auth.register')->middleware('auth', 'can:admin');
    Route::post('/register', [AuthController::class, 'store'])->name('auth.store')->middleware('auth', 'can:admin');

    Route::get('/{id}/edit', [AuthController::class, 'edit'])->name('auth.edit')->middleware('auth', 'can:admin');
    Route::post('/{id}/update', [AuthController::class, 'update'])->name('auth.update')->middleware('auth', 'can:admin');

    Route::post('/{id}/destroy', [AuthController::class, 'destroy'])->name('auth.destroy')->middleware('auth', 'can:admin');

    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout')->middleware('auth');
});

Route::group(['prefix' => '/', 'middleware' => 'auth'], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/wallet', [EmployeeController::class, 'wallet'])->name('employee.wallet');
});

Route::group(['prefix' => '/jobs', 'middleware' => ['auth', 'can:admin']], function () {
    Route::get('/', [JobController::class, 'index'])->name('jobs.index');
    Route::get('/create', [JobController::class, 'create'])->name('jobs.create');
    Route::post('/store', [JobController::class, 'store'])->name('jobs.store');

    Route::get('/edit/{id}', [JobController::class, 'edit'])->name('jobs.edit');
    Route::post('/update/{id}', [JobController::class, 'update'])->name('jobs.update');

    Route::get('/destroy/{id}', [JobController::class, 'destroy'])->name('jobs.destroy');
});

Route::group(['prefix' => '/expensecategories', 'middleware' => ['auth', 'can:admin']], function () {
    Route::get('/', [ExpenseCategoryController::class, 'index'])->name('expensecategories.index');
    Route::get('/create', [ExpenseCategoryController::class, 'create'])->name('expensecategories.create');
    Route::post('/store', [ExpenseCategoryController::class, 'store'])->name('expensecategories.store');

    Route::get('/edit/{id}', [ExpenseCategoryController::class, 'edit'])->name('expensecategories.edit');
    Route::post('/update/{id}', [ExpenseCategoryController::class, 'update'])->name('expensecategories.update');

    Route::get('/destroy/{id}', [ExpenseCategoryController::class, 'destroy'])->name('expensecategories.destroy');
});

Route::group(['prefix' => '/employees', 'middleware' => ['auth']], function () {
    Route::get('/', [EmployeeController::class, 'index'])->name('employees.index')->middleware('can:admin');
    Route::get('/create', [EmployeeController::class, 'create'])->name('employees.create')->middleware('can:admin');
    Route::post('/store', [EmployeeController::class, 'store'])->name('employees.store')->middleware('can:admin');

    Route::get('/edit/{id}', [EmployeeController::class, 'edit'])->name('employees.edit')->middleware('can:admin');
    Route::post('/update/{id}', [EmployeeController::class, 'update'])->name('employees.update')->middleware('can:admin');

    Route::get('/addbalance/{id}', [EmployeeController::class, 'addbalance'])->name('employees.addbalance')->middleware('can:admin');
    Route::post('/addbalance/{id}', [EmployeeController::class, 'addbalance'])->name('employees.addbalance')->middleware('can:admin');

    Route::get('/destroy/{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy')->middleware('can:admin');

    Route::get('/vouchers', [VoucherController::class, 'approvalRequests'])->name('employees.approvalRequests')->middleware('can:admin');
    Route::get('/vouchers/approved', [VoucherController::class, 'approvedVouchers'])->name('employees.approvedVouchers');
    Route::get('/vouchers/rejected', [VoucherController::class, 'rejectedVouchers'])->name('employees.rejectedVouchers');
    Route::get('/vouchers/details/{id}', [VoucherController::class, 'voucherDetails'])->name('employees.voucherDetails');
    Route::get('/vouchers/pdf/{id}', [VoucherController::class, 'voucherDetailsPdf'])->name('employees.voucherDetailsPdf')->middleware('can:admin');
    Route::post('/vouchers/approvereject', [VoucherController::class, 'voucherApproveReject'])->name('vouchers.voucherApproveReject')->middleware('can:admin');
    Route::post('/vouchers/savedraft', [VoucherController::class, 'voucherSaveDraft'])->name('vouchers.voucherSaveDraft')->middleware('can:admin');

    Route::post('/vouchers/expense/addbills', [VoucherController::class, 'addExpenseBills'])->name('vouchers.addExpenseBills')->middleware('can:admin');
});

Route::group(['prefix' => '/vouchers', 'middleware' => ['auth', 'can:employee']], function () {
    Route::get('/', [VoucherController::class, 'index'])->name('vouchers.index');
    Route::get('/create', [VoucherController::class, 'create'])->name('vouchers.create');
    Route::post('/store', [VoucherController::class, 'store'])->name('vouchers.store');

    Route::get('/edit/{id}', [VoucherController::class, 'edit'])->name('vouchers.edit');
    Route::post('/update/{id}', [VoucherController::class, 'update'])->name('vouchers.update');

    Route::post('/createExpense/{id}', [VoucherController::class, 'createExpense'])->name('vouchers.createExpense');
    Route::post('/updateExpense/{id}', [VoucherController::class, 'updateExpense'])->name('vouchers.updateExpense');
    Route::post('/destroyExpense', [VoucherController::class, 'destroyExpense'])->name('vouchers.destroyExpense');
    Route::post('/attachAdditionalFiles/{id}', [VoucherController::class, 'attachAdditionalFiles'])->name('vouchers.attachAdditionalFiles');

    Route::post('/askForApproval', [VoucherController::class, 'askForApproval'])->name('vouchers.askForApproval');

    Route::get('/destroy/{id}', [VoucherController::class, 'destroy'])->name('vouchers.destroy');
});

Route::group(['prefix' => '/payments', 'middleware' => ['auth', 'can:admin']], function () {
    Route::get('/', [PaymentController::class, 'index'])->name('payments.index');
});
