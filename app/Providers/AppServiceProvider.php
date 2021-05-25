<?php

namespace App\Providers;

use App\Models\Voucher;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Voucher::created(function ($voucher) {
            $number = auth()->user()->employee->code . '-' . $voucher->id;
            $voucher->number = $number;
            $voucher->save();
        });
    }
}
