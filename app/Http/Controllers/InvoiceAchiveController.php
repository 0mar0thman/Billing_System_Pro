<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;

class InvoiceAchiveController extends Controller
{
    public function index()
    {
        $invoices = Invoice::onlyTrashed()->get();
        return view('invoices.Archive_Invoices', compact('invoices'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id'
        ]);

        try {
            $invoice = Invoice::withTrashed()->where('id', $request->invoice_id)->firstOrFail();
            $invoice->restore();

            return redirect('/invoices')->with('success', 'تم استعادة الفاتورة بنجاح');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء استعادة الفاتورة');
        }
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id'
        ]);

        try {
            $invoice = Invoice::withTrashed()->where('id', $request->invoice_id)->firstOrFail();
            $invoice->forceDelete();

            return redirect('/Archive')->with('success', 'تم حذف الفاتورة نهائياً بنجاح');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف الفاتورة');
        }
    }
}
