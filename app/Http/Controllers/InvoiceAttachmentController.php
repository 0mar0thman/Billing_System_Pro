<?php

namespace App\Http\Controllers;

use App\Models\InvoiceAttachments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceAttachmentController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. فاليديشن
        $this->validate($request, [
            'file_name' => 'required|file|mimes:pdf,jpeg,png,jpg',
        ], [
            'file_name.required' => 'يجب ارفاق ملف',
            'file_name.mimes' => 'صيغة المرفق يجب ان تكون pdf, jpeg , png , jpg',
        ]);

        // 2. استلام الملف
        $image = $request->file('file_name');

        // 3. تجهيز الاسم
        $file_name = time() . '_' . preg_replace('/\s+/', '_', $image->getClientOriginalName());

        // 4. إنشاء مجلد التخزين لو مش موجود
        $destinationPath = public_path('Attachments/' . $request->invoice_id);
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }
        // 5. حفظ البيانات في الداتا بيز
        $attachments = new InvoiceAttachments();
        $attachments->file_name = $file_name;
        $attachments->invoice_id = $request->invoice_id;
        $attachments->Created_by = Auth::user()->name;
        $attachments->save();

        // 6. نقل الملف
        $image->move($destinationPath, $file_name);

        // 7. فلاش ميسج ورجوع
        session()->flash('Add', 'تم اضافة المرفق بنجاح');
        return back()->withFragment('tab5');
    }



    public function edit(InvoiceAttachments $invoice_attachments)
    {
        $invoice_attachments = InvoiceAttachments::all();
        $attachments = InvoiceAttachments::with('invoice.section')->get();
        return view('invoices.details_invoices', compact('invoice_attachments', 'attachments'));
    }
}
