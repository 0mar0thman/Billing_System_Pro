<?php

namespace App\Services;

use App\Models\invoices;
use App\Models\invoices_details;
use App\Models\invoice_attachments;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InvoiceService
{
    public function createInvoice(array $data)
    {
        $invoice = invoices::create([
            'invoice_Date' => $data['invoice_Date'],
            'Due_date' => $data['Due_date'],
            'product' => $data['product'],
            'product_name' => $data['product_name'] ?? null,
            'section_id' => $data['Section'],
            'Amount_collection' => $data['Amount_collection'],
            'Amount_Commission' => $data['Amount_Commission'],
            'Discount_Commission' => $data['Discount_Commission'] ?? 0,
            'Value_VAT' => $data['Value_VAT'],
            'Rate_VAT' => $data['Rate_VAT'] ?? 0,
            'Total' => $data['Total'],
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $data['note'] ?? "لا يوجد ملاحظات",
        ]);

        invoices_details::create([
            'invoice_id' => $invoice->id,
            'product_id' => $data['product_id'],
            'section_id' => $data['section_id'],
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $data['note'] ?? "لا يوجد ملاحظات",
            'user' => Auth::user()->name,
        ]);

        return $invoice;
    }

    public function handleAttachment($request, $invoiceId)
    {
        if ($request->hasFile('pic')) {
            $image = $request->file('pic');
            $file_name = $image->getClientOriginalName();

            $attachments = new invoice_attachments();
            $attachments->file_name = $file_name;
            $attachments->Created_by = Auth::user()->name;
            $attachments->invoice_id = $invoiceId;
            $attachments->save();

            $imageName = $request->pic->getClientOriginalName();
            $request->pic->move(public_path('Attachments/' . $invoiceId), $imageName);
        }
    }

    public function deleteInvoice($id, $forceDelete = false)
    {
        $invoice = invoices::withTrashed()->where('id', $id)->first();

        if (!$invoice) {
            return false;
        }

        $Details = invoice_attachments::where('invoice_id', $id)->first();

        if ($forceDelete) {
            if ($Details && !empty($Details->invoice_number)) {
                Storage::disk('public_upload')->deleteDirectory($Details->invoice_number);
            }
            return $invoice->forceDelete();
        }

        return $invoice->delete();
    }
}
