<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;
use App\Models\SalesInvoice;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/sales-invoices/invoices/{id}', [InvoiceController::class, 'show'])->name('InvoiceSales');
Route::get('/purchase-invoices/invoices/{id}', [InvoiceController::class, 'showPurchase'])->name('purchaseInvoice');
// Route::get('/sales-invoices/invoices/{id}', function () {
//     dd('ini link');
// })->name('InvoiceSales');
