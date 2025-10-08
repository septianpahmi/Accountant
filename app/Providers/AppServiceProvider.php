<?php

namespace App\Providers;

use App\Models\SalesInvoice;
use App\Observers\SalesInvoiceObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        SalesInvoice::observe(SalesInvoiceObserver::class);
    }
}
