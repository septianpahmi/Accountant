<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseInvoice;
use App\Models\SalesInvoice;

class InvoiceController extends Controller
{
    public function show($id)
    {
        $record = SalesInvoice::findOrFail($id);
        return view('invoice.sales', compact('record'));
    }
    public function showPurchase($id)
    {
        $record = PurchaseInvoice::findOrFail($id);
        return view('invoice.purchase', compact('record'));
    }
}
