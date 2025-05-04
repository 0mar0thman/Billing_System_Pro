<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceDetails;
use App\Models\InvoiceAttachments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class InvoiceDetailController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $invoice = Invoice::where('id', $id)->first();
        $invoices_details = InvoiceDetails::where('invoice_id', $id)->get();
        $attachments = InvoiceAttachments::where('invoice_id', $id)->get();

        $invoices_details_address = InvoiceDetails::where('invoice_id', $id)->first();

        return view('invoices.details_invoices', compact('invoice', 'invoices_details',  'attachments', 'invoices_details_address'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $attachment = InvoiceAttachments::find($id);
        if ($attachment) {
            Storage::disk('public_upload')->delete($attachment->invoice_number . '/' . $attachment->file_name);
            $attachment->delete();

            return redirect()->back()->with('success', 'تم حذف الملف بنجاح');
        }
        return redirect()->back()->with('error', 'لم يتم العثور على الملف');
    }
}
