<?php

namespace App\Http\Controllers;

use App\Models\InvoiceSittings;
use Illuminate\Http\Request;

class InvoiceSittingController extends Controller
{
    public function index()
    {
        $SittingsInvoices = InvoiceSittings::all();
        return view('invoices.sittings_invoices', compact('SittingsInvoices'));
    }

    public function store(Request $request)
    {
        InvoiceSittings::create([
            'Discount_Commission' => $request->Discount_Commission,
            'Amount_Commission' => $request->Amount_Commission,
            'Amount_collection' => $request->Amount_collection,
        ]);

        return redirect(route('invoices.create'));
    }
}
