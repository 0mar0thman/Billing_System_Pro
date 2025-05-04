<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Section;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\InvoiceDetails;
use App\Models\InvoiceAttachments;
use App\Models\InvoiceSittings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Notification;
use App\Notifications\NoteInvoice;

use App\Exports\InvoicesExport;
use Maatwebsite\Excel\Facades\Excel;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::all();
        return view('invoices.invoices', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $SittingsInvoices = InvoiceSittings::latest()->first();
        $sections = Section::with('products')->get();
        $invoices =  Invoice::max('id');

        // dd($sections);
        return view('invoices.add_invoice', compact('sections', 'invoices', 'SittingsInvoices'));
    }

    public function store(Request $request)
    {
        // dd($request);
        $messages = [
            'invoice_Date.required' => 'تاريخ الفاتورة مطلوب',
            'invoice_Date.date' => 'يجب أن يكون تاريخ الفاتورة صالحاً',
            'Due_date.required' => 'تاريخ الاستحقاق مطلوب',
            'Due_date.date' => 'يجب أن يكون تاريخ الاستحقاق صالحاً',
            'Due_date.after_or_equal' => 'تاريخ الاستحقاق يجب أن يكون بعد أو يساوي تاريخ الفاتورة',
            'section_id.required' => 'اختيار القسم مطلوب',
            'section_id.exists' => 'القسم المحدد غير موجود',
            'product_id.required' => 'اختيار المنتج مطلوب',
            'product_id.exists' => 'المنتج المحدد غير موجود',
            'Amount_collection.required' => 'مبلغ التحصيل مطلوب',
            'Amount_collection.numeric' => 'مبلغ التحصيل يجب أن يكون رقماً',
            'Amount_collection.min' => 'مبلغ التحصيل يجب أن يكون أكبر من صفر',
            'Amount_Commission.required' => 'مبلغ العمولة مطلوب',
            'Amount_Commission.numeric' => 'مبلغ العمولة يجب أن يكون رقماً',
            'Amount_Commission.min' => 'مبلغ العمولة يجب أن يكون أكبر من صفر',
            'Discount_Commission.required' => 'قيمة الخصم مطلوبة',
            'Discount_Commission.numeric' => 'قيمة الخصم يجب أن تكون رقماً',
            'Discount_Commission.min' => 'قيمة الخصم يجب أن تكون أكبر من صفر',
            'Value_VAT.required' => 'قيمة الضريبة مطلوبة',
            'Value_VAT.numeric' => 'قيمة الضريبة يجب أن تكون رقماً',
            'Value_VAT.min' => 'قيمة الضريبة يجب أن تكون أكبر من صفر',
            'Rate_VAT.required' => 'نسبة الضريبة مطلوبة',
            'Rate_VAT.numeric' => 'نسبة الضريبة يجب أن تكون رقماً',
            'Rate_VAT.min' => 'نسبة الضريبة يجب أن تكون أكبر من صفر',
            'Rate_VAT.max' => 'نسبة الضريبة يجب أن لا تتجاوز 100%',
            'Total.required' => 'المبلغ الإجمالي مطلوب',
            'Total.numeric' => 'المبلغ الإجمالي يجب أن يكون رقماً',
            'Total.min' => 'المبلغ الإجمالي يجب أن يكون أكبر من صفر',
            'Value_Status.required' => 'حالة الدفع مطلوبة',
            'Value_Status.in' => 'حالة الدفع غير صالحة',
            'note.max' => 'يجب أن لا تتجاوز الملاحظات 500 حرف',
            'pic.file' => 'يجب أن يكون المرفق ملفاً صالحاً',
            'pic.mimes' => 'يجب أن يكون المرفق من نوع: jpeg, png, jpg, pdf',
            'pic.max' => 'يجب أن لا يتجاوز حجم المرفق 2 ميجابايت',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'يجب أن يكون البريد الإلكتروني صالحاً',
            'address.required' => 'العنوان مطلوب',
            'address.max' => 'يجب أن لا يتجاوز العنوان 255 حرفاً',
            'phone.required' => 'رقم الهاتف مطلوب',
            'phone.max' => 'يجب أن لا يتجاوز رقم الهاتف 20 رقماً',
        ];

        $validator = Validator::make($request->all(), [
            'invoice_Date' => 'required|date',
            'Due_date' => 'required|date|after_or_equal:invoice_Date',
            'section_id' => 'required|exists:sections,id',
            'product_id' => 'required|exists:products,id',
            'Amount_collection' => 'required|numeric|min:0',
            'Amount_Commission' => 'nullable|numeric|min:0',
            'Discount_Commission' => 'nullable|numeric|min:0',
            'Value_VAT' => 'nullable|numeric|min:0',
            'Rate_VAT' => 'required|numeric|min:0',
            'Total' => 'nullable|numeric|min:0',
            'Value_Status' => 'required|in:1,2,3',
            'note' => 'nullable|string|max:500',
            'pic' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'email' => 'required|email',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ], $messages);


        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // إضافة الفاتورة
        $Status = $request->Value_Status == 1
            ? 'مدفوعة'
            : ($request->Value_Status == 2
                ? 'غير مدفوعة'
                : 'مدفوع جزئيا');

        // إنشاء الفاتورة
        // $section_name = $invoice->section->name;
        $section_name = Section::where('id', $request->section_id)->value('section_name');

        $invoice = Invoice::create([
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product_id' => $request->product_id,
            'section_id' => $request->section_id,
            'product_name' => $request->product_name,
            'section_name' => $section_name,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount_Commission' => $request->Discount_Commission,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT ?? 0,
            'Total' => $request->Total,
            'Status' => $Status,
            'Value_Status' => $request->Value_Status,
            'note' => $request->note ?? "لا يوجد ملاحظات",
            'Payment_Date' => $request->invoice_Date,
        ]);

        $invoice_id = $invoice->id;

        // إدخال تفاصيل الفاتورة
        InvoiceDetails::create([
            'invoice_id' => $invoice_id,
            'product_name' => $request->product_name,
            'section_name' => $section_name,
            'email' => $request->email,
            'address' => $request->address,
            'phone' => $request->phone,
            'product_id' => $request->product_id,
            'section_id' => $request->section_id,
            'Status' => $Status,
            'Value_Status' => $request->Value_Status,
            'Payment_Date' => $request->invoice_Date,
            'note' => $request->note ?? "لا يوجد ملاحظات",
            'user' => Auth::user()->name,
        ]);

        // التعامل مع الصورة المرفقة
        if ($request->hasFile('pic')) {
            $image = $request->file('pic');
            $file_name = $image->getClientOriginalName();

            // إنشاء سجل في جدول المرفقات
            InvoiceAttachments::create([
                'file_name' => $file_name,
                'Created_by' => Auth::user()->name,
                'invoice_id' => $invoice_id,
            ]);

            // حفظ الصورة في المجلد المناسب
            $destinationPath = public_path('Attachments/' . $invoice_id);
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $image->move($destinationPath, $file_name);
        }


        $invoices = Invoice::latest()->first();
        // $user = User::where('is_admin', 1)->get();
        $user = User::get();
        Notification::send($user, new NoteInvoice($invoices));

        session()->flash('Add', 'تم اضافة الفاتورة بنجاح');
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $invoices = Invoice::where('id', $id)->first();
        return view('invoices.status_update', compact('invoices'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $invoices = Invoice::with(['section'])->findOrFail($id);
        // جلب المنتجات الخاصة بالقسم المحدد في الفاتورة
        $products = Product::where('section_id', $invoices->section_id)->get();
        return view('invoices.edit_invoice', compact('invoices',  'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);

        $invoice->update([
            'invoice_Date'       => $request->invoice_Date,
            'Due_date'           => $request->Due_date,
            'product_name'       => $request->product,
            'Amount_collection'  => $request->Amount_collection,
            'Amount_Commission'  => $request->Amount_Commission,
            'Discount'           => $request->Discount,
            'Rate_VAT'           => $request->Rate_VAT,
            'Value_VAT'          => $request->Value_VAT,
            'Total'              => $request->Total,
            'note'               => $request->note ?? "لا يوجد ملاحظات",
        ]);

        // dd($request);
        session()->flash('edit', 'تم تعديل الفاتورة بنجاح');
        return redirect()->route('invoices.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->invoice_id;
        $invoices = Invoice::where('id', $id)->first();
        $Details = InvoiceAttachments::where('invoice_id', $id)->first();

        $id_page = $request->id_page;

        if (!$invoices) {
            return redirect()->back()->with('error', 'Invoice not found.');
        }

        if (!$id_page == 2) {

            if (!empty($Details->invoice_number)) {

                Storage::disk('public_upload')->deleteDirectory($Details->invoice_number);
            }

            $invoices->foreceDelete();
            session()->flash('delete_invoice');
            return redirect('/invoices');
        } else {

            $invoices->delete();
            session()->flash('archive_invoice');
            return redirect('/Archive');
        }
    }

    public function getproducts($id)
    {
        $products = DB::table("products")->where("section_id", $id)->pluck("product_name", "id");
        return json_encode($products);
    }



    public function getProductDetails($id)
    {
        try {
            $product = Product::findOrFail($id);
            return response()->json([
                'product_name' => $product->product_name,
                'email' => $product->email,
                'phone' => $product->phone,
                'address' => $product->address
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'حدث خطأ في جلب البيانات'], 500);
        }
    }

    public function Status_Update(Request $request, $id)
    {
        $invoices = Invoice::findOrFail($id);
        $section_name = Section::where('id', $request->section_id)->value('section_name');

        if ($request->Status === 'مدفوعة') {
            $valueStatus = 1;
        } elseif ($request->Status === 'غير مدفوعة') {
            $valueStatus = 2;
        } else { // مدفوع جزئيا
            $valueStatus = 3;
        }

        $invoices->update([
            'Value_Status' => $valueStatus,
            'Status' => $request->Status,
            'Payment_Date' => $request->Payment_Date,
        ]);

        InvoiceDetails::create([
            'invoice_id' => $request->invoice_id,
            'product_name' => $request->product_name,
            'section_name' => $section_name,
            'Status' => $request->Status,
            'Value_Status' => $valueStatus,
            'note' => $request->note,
            'Payment_Date' => $request->Payment_Date,
            'user' => Auth::user()->name,
        ]);

        session()->flash('Status_Update');
        return redirect('/invoices');
        // return back();
    }


    public function Invoice_Paid()
    {
        $invoices = Invoice::where('Value_Status', 1)->get();
        return view('invoices.invoices_paid', compact('invoices'));
    }

    public function Invoice_unPaid()
    {
        $invoices = Invoice::where('Value_Status', 2)->get();
        return view('invoices.invoices_unpaid', compact('invoices'));
    }

    public function Invoice_Partial()
    {
        $invoices = Invoice::where('Value_Status', 3)->get();
        return view('invoices.invoices_Partial', compact('invoices'));
    }
    public function print($id)
    {


        $invoice = Invoice::findOrFail($id);
        return view('invoices.print_invoice', compact('invoice'));
    }

    public function export()
    {
        return Excel::download(new InvoicesExport, 'users.xlsx');
    }
}
