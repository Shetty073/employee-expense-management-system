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
    Route::post('/store', [JobController::class, 'store'])->name('jobs.store');

    Route::get('/edit/{id}', [JobController::class, 'edit'])->name('jobs.edit');
    Route::post('/update/{id}', [JobController::class, 'update'])->name('jobs.update');

    Route::get('/destroy/{id}', [JobController::class, 'destroy'])->name('jobs.destroy');
});

Route::group(['prefix' => '/expensecategories'/*, 'middleware' => 'auth'*/], function () {
    Route::get('/', [ExpenseCategoryController::class, 'index'])->name('expensecategories.index');
    Route::get('/create', [ExpenseCategoryController::class, 'create'])->name('expensecategories.create');
    Route::post('/store', [ExpenseCategoryController::class, 'store'])->name('expensecategories.store');

    Route::get('/edit/{id}', [ExpenseCategoryController::class, 'edit'])->name('expensecategories.edit');
    Route::post('/update/{id}', [ExpenseCategoryController::class, 'update'])->name('expensecategories.update');

    Route::get('/destroy/{id}', [ExpenseCategoryController::class, 'destroy'])->name('expensecategories.destroy');
});

Route::group(['prefix' => '/employees'/*, 'middleware' => 'auth'*/], function () {
    Route::get('/', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/create', [EmployeeController::class, 'create'])->name('employees.create');
    Route::post('/store', [EmployeeController::class, 'store'])->name('employees.store');

    Route::get('/edit/{id}', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::post('/update/{id}', [EmployeeController::class, 'update'])->name('employees.update');

    Route::get('/destroy/{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
});


