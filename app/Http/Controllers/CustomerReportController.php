<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Section;
use App\Models\products;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class CustomerReportController extends Controller
{
    public function index()
    {
        $sections = Section::with('products')->get();
        return view('reports.customers_report', compact('sections'));
    }

    public function Search_customers(Request $request)
    {
        // في حالة البحث بدون التاريخ
        if ($request->filled('section_id') && $request->filled('product_id') && empty($request->start_at) && empty($request->end_at)) {
            $invoices = Invoice::where('section_id', $request->section_id)
                ->where('product_id', $request->product_id)
                ->get();

            $sections = Section::all();
            return view('reports.customers_report', compact('invoices', 'sections'));
        }
        // في حالة البحث بتاريخ
        else {

            $start_at = Carbon::parse($request->start_at)->format('Y-m-d');
            $end_at = Carbon::parse($request->end_at)->format('Y-m-d');

            $invoices = Invoice::whereBetween('invoice_Date', [$start_at, $end_at])
                ->where('section_id', $request->section_id)
                ->where('product_id', $request->product_id)
                ->get();

            $sections = Section::all();
            return view('reports.customers_report', compact('invoices', 'sections', 'start_at', 'end_at'));
        }
    }


    // في Controller
    public function getProducts($id)
    {
        $products = DB::table("products")->where("section_id", $id)->pluck("product_name", "id");
        return json_encode($products);
    }
}
