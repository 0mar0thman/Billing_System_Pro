<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductsExport;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        $sections = Section::all();
        return view("products.products", compact("products", "sections"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|max:255',
            // 'description' => 'required',
        ], [
            'product_name.required' => 'يجب ادخال القسم ',
            // 'description.required' => 'يجب ادخال وصف القسم',
        ]);

        Product::create([
            'product_name' => $request->product_name,
            'description' => $request->description ?? 'لا توجد ملاحظات',
            'section_id' => $request->section_id,
            'phone' => $request->phone,
            'address' => $request->address,
            'email' => $request->email,
        ]);
        session()->flash('Add', 'تم اضافة المنتج بنجاح');
        return redirect('/products');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'Product_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'section_name' => 'required|string|max:255',
        ], [
            'Product_name.required' => 'اسم المنتج مطلوب',
            'Product_name.string' => 'اسم المنتج يجب أن يكون نصاً',
            'Product_name.max' => 'اسم المنتج يجب ألا يزيد عن 255 حرفاً',

            'description.string' => 'الوصف يجب أن يكون نصاً',
            'description.max' => 'الوصف يجب ألا يزيد عن 500 حرفاً',

            'section_name.required' => 'اسم القسم مطلوب',
            'section_name.string' => 'اسم القسم يجب أن يكون نصاً',
            'section_name.max' => 'اسم القسم يجب ألا يزيد عن 255 حرفاً',
        ]);;

        $id = Section::where('section_name', $request->section_name)->first()->id;

        $Products = Product::findOrFail(id: $request->pro_id);

        $Products->update([
            'product_name' => $request->Product_name,
            'description' => $request->description,
            'section_id' => $id,
        ]);

        session()->flash('Edit', 'تم تعديل المنتج بنجاح');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $Products = Product::findOrFail($request->pro_id);
        $Products->delete();
        session()->flash('delete', 'تم حذف المنتج بنجاح');
        return back();
    }
    public function export()
    {
        return Excel::download(new ProductsExport, 'clients.xlsx');
    }
}
