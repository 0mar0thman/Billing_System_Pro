<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'invoice_Date' => 'required|date',
            'Due_date' => 'required|date|after_or_equal:invoice_Date',
            'product' => 'required|string|max:255',
            'Section' => 'required|exists:sections,id',
            'Amount_collection' => 'required|numeric|min:0',
            'Amount_Commission' => 'required|numeric|min:0',
            'Discount_Commission' => 'nullable|numeric|min:0|max:100',
            'Value_VAT' => 'required|numeric|min:0',
            'Rate_VAT' => 'required|in:5,10',
            'Total' => 'required|numeric|min:0',
            'note' => 'nullable|string|max:500',
            'pic' => 'nullable|mimes:jpeg,png,jpg,gif,pdf|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'invoice_Date.required' => 'حقل تاريخ الفاتورة مطلوب',
            'invoice_Date.date' => 'تاريخ الفاتورة غير صالح',
            'Due_date.required' => 'حقل تاريخ الاستحقاق مطلوب',
            'Due_date.date' => 'تاريخ الاستحقاق غير صالح',
            'Due_date.after_or_equal' => 'تاريخ الاستحقاق يجب أن يكون بعد أو يساوي تاريخ الفاتورة',
            'product.required' => 'حقل المنتج مطلوب',
            'product.max' => 'اسم المنتج يجب ألا يتجاوز 255 حرفًا',
            'Section.required' => 'حقل القسم مطلوب',
            'Section.exists' => 'القسم المحدد غير موجود',
            'Amount_collection.required' => 'حقل مبلغ التحصيل مطلوب',
            'Amount_collection.numeric' => 'مبلغ التحصيل يجب أن يكون رقمًا',
            'Amount_collection.min' => 'مبلغ التحصيل يجب أن يكون على الأقل 0',
            'Amount_Commission.required' => 'حقل مبلغ العمولة مطلوب',
            'Amount_Commission.numeric' => 'مبلغ العمولة يجب أن يكون رقمًا',
            'Amount_Commission.min' => 'مبلغ العمولة يجب أن يكون على الأقل 0',
            'Discount_Commission.numeric' => 'الخصم يجب أن يكون رقمًا',
            'Discount_Commission.min' => 'الخصم يجب أن يكون على الأقل 0',
            'Discount_Commission.max' => 'الخصم يجب ألا يتجاوز 100%',
            'Value_VAT.required' => 'حقل قيمة الضريبة مطلوب',
            'Value_VAT.numeric' => 'قيمة الضريبة يجب أن تكون رقمًا',
            'Value_VAT.min' => 'قيمة الضريبة يجب أن تكون على الأقل 0',
            'Rate_VAT.required' => 'حقل نسبة الضريبة مطلوب',
            'Rate_VAT.in' => 'نسبة الضريبة يجب أن تكون إما 5% أو 10%',
            'Total.required' => 'حقل الإجمالي مطلوب',
            'Total.numeric' => 'الإجمالي يجب أن يكون رقمًا',
            'Total.min' => 'الإجمالي يجب أن يكون على الأقل 0',
            'note.max' => 'الملاحظات يجب ألا تتجاوز 500 حرف',
            'pic.mimes' => 'يجب أن يكون الملف من نوع: jpeg, png, jpg, gif, pdf',
            'pic.max' => 'حجم الملف يجب ألا يتجاوز 2 ميجابايت',
        ];
    }
}
