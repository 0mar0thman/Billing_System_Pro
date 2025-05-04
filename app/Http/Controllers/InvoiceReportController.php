<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use Carbon\Carbon;

class InvoiceReportController extends Controller
{
    public function index(Request $request)
    {
        // حفظ حالة البحث السابقة أو استخدام القيم الافتراضية
        $searchType = $request->session()->get('invoice_report.search_type', 1);
        $type = $request->session()->get('invoice_report.type', 'حدد نوع الفواتير');
        $invoiceNumber = $request->session()->get('invoice_report.invoice_number', '');
        $startAt = $request->session()->get('invoice_report.start_at', '');
        $endAt = $request->session()->get('invoice_report.end_at', '');

        return view('reports.invoices_report', compact(
            'searchType',
            'type',
            'invoiceNumber',
            'startAt',
            'endAt'
        ));
    }

    public function search(Request $request)
    {
        $request->validate([
            'rdio' => 'required|in:1,2',
            'type' => 'required_if:rdio,1',
            'invoice_number' => 'required_if:rdio,2',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after_or_equal:start_at'
        ]);

        // حفظ معايير البحث في الجلسة
        $request->session()->put('invoice_report.search_type', $request->rdio);
        $request->session()->put('invoice_report.type', $request->type ?? 'حدد نوع الفواتير');
        $request->session()->put('invoice_report.invoice_number', $request->invoice_number ?? '');
        $request->session()->put('invoice_report.start_at', $request->start_at ?? '');
        $request->session()->put('invoice_report.end_at', $request->end_at ?? '');

        if ($request->rdio == 1) {
            return $this->searchByType($request);
        }

        return $this->searchByNumber($request);
    }

    protected function searchByType($request)
    {
        $query = Invoice::query();

        if ($request->type != 'الكل') {
            $query->where('Status', $request->type);
        }

        if ($request->filled('start_at') && $request->filled('end_at')) {
            $startAt = Carbon::parse($request->start_at)->startOfDay();
            $endAt = Carbon::parse($request->end_at)->endOfDay();
            $query->whereBetween('invoice_Date', [$startAt, $endAt]);
        }

        $invoices = $query->with('sections')->latest()->get();

        return view('reports.invoices_report', [
            'invoices' => $invoices,
            'searchType' => 1,
            'type' => $request->type,
            'startAt' => $request->start_at,
            'endAt' => $request->end_at,
            'invoiceNumber' => ''
        ]);
    }

    // في InvoiceReportController
    protected function searchByNumber($request)
    {
        $request->validate([
            'rdio' => 'required|in:1,2',
            'type' => 'required_if:rdio,1',
            'invoice_number' => 'required_if:rdio,2|string',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after_or_equal:start_at'
        ], [
            'type.required_if' => 'حقل نوع الفاتورة مطلوب عند البحث بالنوع',
            'invoice_number.required_if' => 'حقل رقم الفاتورة مطلوب عند البحث بالرقم'
        ]);

        $invoice = Invoice::where('id', $request->invoice_number)   ->first();

        if (!$invoice) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['invoice_number' => 'رقم الفاتورة غير موجود']);
        }
        // dd($invoice);

         return view('reports.invoices_report', [
        'invoices' => $invoice ? collect([$invoice]) : collect(),
        'searchType' => 2,
        'invoiceNumber' => $request->invoice_number,
        'type' => $request->session()->get('invoice_report.type', ''),
        'startAt' => $request->session()->get('invoice_report.start_at', ''),
        'endAt' => $request->session()->get('invoice_report.end_at', '')
    ]);

    }
}
