<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Product;
use App\Models\Invoice;
use App\Models\InvoiceDetails;
use App\Models\InvoiceAttachments;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{

    public function index()
    {

        $Invoices = Invoice::all();
        // 1. الإحصائيات الأساسية المعززة
        $stats = [
            'total_banks' => Section::count(),
            'active_banks' => Section::with('invoices')->count(),
            'total_clients' => Product::count(),
            'clients_with_contacts' => Product::whereNotNull('phone')->whereNotNull('email')->count(),

            'total_invoices' => Invoice::count(),
            'total_invoices_1' => Invoice::where('Value_Status', 1)->count(),
            'total_invoices_2' => Invoice::where('Value_Status', 2)->count(),
            'total_invoices_3' => Invoice::where('Value_Status', 3)->count(),

            'total_invoices_week' => Invoice::whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ])->count(),
            'total_invoices_week_1' => Invoice::whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ])->where('Value_Status', 1)->count(),
            'total_invoices_week_2' => Invoice::whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ])->where('Value_Status', 2)->count(),
            'total_invoices_week_3' => Invoice::whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ])->where('Value_Status', 3)->count(),

            'total_invoices_month' => Invoice::whereBetween('created_at', [
                now()->startOfMonth(),
                now()->endOfMonth(),
            ])->count(),
            'total_invoices_month_1' => Invoice::whereBetween('created_at', [
                now()->startOfMonth(),
                now()->endOfMonth(),
            ])->where('Value_Status', 1)->count(),
            'total_invoices_month_2' => Invoice::whereBetween('created_at', [
                now()->startOfMonth(),
                now()->endOfMonth(),
            ])->where('Value_Status', 2)->count(),
            'total_invoices_month_3' => Invoice::whereBetween('created_at', [
                now()->startOfMonth(),
                now()->endOfMonth(),
            ])->where('Value_Status', 3)->count(),

            'invoices_with_attachments' => Invoice::with('attachments')->count(),
        ];

        // 2. تحليل متقدم لحالة الفواتير
        $invoiceStatus = $this->getEnhancedInvoiceStatus();

        // 3. تحليل ديون البنوك مع التفاصيل
        $bankAnalysis = $this->getBankDebtAnalysis();

        // 4. تحليل العملاء مع التفاصيل
        $clientAnalysis = $this->getClientAnalysis();

        // 5. الملخص المالي المعزز
        $financialReport = $this->getFinancialReport();

        // 6. تحليل المرفقات
        $attachmentAnalysis = $this->getAttachmentAnalysis();

        // 7. التحليل الزمني
        $timeAnalysis = $this->getTimeAnalysis();

        $chartjs = $this->chartjs();


        return view('home', compact(
            'stats',
            'invoiceStatus',
            'bankAnalysis',
            'clientAnalysis',
            'financialReport',
            'attachmentAnalysis',
            'timeAnalysis',
            'Invoices',
            'chartjs'
        ));
    }

    private function getEnhancedInvoiceStatus()
    {
        // تعريف الحالات الأساسية مع قيم افتراضية
        $statuses = [
            'مدفوعة' => ['count' => 0, 'amount' => 0, 'percentage' => 0],
            'غير مدفوعة' => ['count' => 0, 'amount' => 0, 'percentage' => 0],
            'مدفوعة جزئيا' => ['count' => 0, 'amount' => 0, 'percentage' => 0]
        ];

        // الحصول على البيانات من قاعدة البيانات
        $dbStatuses = Invoice::select('Value_Status', 'Status')
            ->selectRaw('COUNT(*) as count, SUM(Total) as total')
            ->groupBy('Value_Status', 'Status')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item['Status'] => [
                    'count' => $item->count,
                    'amount' => $item->total,
                    'percentage' => round(($item->count / Invoice::count()) * 100, 2)
                ]];
            });

        // دمج البيانات مع القيم الافتراضية
        foreach ($dbStatuses as $key => $value) {
            if (array_key_exists($key, $statuses)) {
                $statuses[$key] = $value;
            }
        }

        // نفس المنطق للبيانات الأسبوعية والشهرية
        $weekly = $this->getTimeBasedStats('week');
        $monthly = $this->getTimeBasedStats('month');

        return [
            'statuses' => $statuses,
            'weekly' => array_merge([
                'مدفوعة' => ['count' => 0, 'amount' => 0],
                'غير مدفوعة' => ['count' => 0, 'amount' => 0],
                'مدفوعة جزئيا' => ['count' => 0, 'amount' => 0]
            ], $weekly->toArray()),
            'monthly' => array_merge([
                'مدفوعة' => ['count' => 0, 'amount' => 0],
                'غير مدفوعة' => ['count' => 0, 'amount' => 0],
                'مدفوعة جزئيا' => ['count' => 0, 'amount' => 0]
            ], $monthly->toArray()),
            'all' => Invoice::sum('Total'),
            'all_1' => Invoice::where('Value_Status', 1)->sum('Total'),
            'all_2' => Invoice::where('Value_Status', 2)->sum('Total'),
            'all_3' => Invoice::where('Value_Status', 3)->sum('Total'),
            'overdue' => [
                'count' => Invoice::where('Due_date', '<', now())->where('Value_Status', 2)->count(),
                'amount' => Invoice::where('Due_date', '<', now())->where('Value_Status', 2)->sum('Total')
            ]
        ];
    }

    private function getBankDebtAnalysis()
    {
        return Section::with(['invoices' => function ($query) {
            $query->where('Value_Status', 2);
        }])
            ->get()
            ->map(function ($section) {
                return [
                    'name' => $section->section_name,
                    'debt' => $section->invoices->sum('Total'),
                    'clients' => $section->products->count(),
                    'contact_info' => [
                        'complete' => $section->products()->whereNotNull('phone')->whereNotNull('email')->count(),
                        'incomplete' => $section->products()->whereNull('phone')->orWhereNull('email')->count()
                    ]
                ];
            })
            ->sortByDesc('debt');
    }

    private function getClientAnalysis()
    {
        return [
            'top_debtors' => Product::with(['sections', 'invoices' => function ($query) {
                $query->where('Value_Status', 2);
            }])
                ->get()
                ->map(function ($product) {
                    return [
                        'name' => $product->product_name,
                        'debt' => $product->invoices->sum('Total'),
                        'section' => $product->sections->section_name ?? 'غير محدد',
                        'contact' => [
                            'phone' => $product->phone,
                            'email' => $product->email,
                            'address' => $product->address
                        ],
                        'last_invoice' => $product->invoices->sortByDesc('invoice_date')->first()
                    ];
                })
                ->sortByDesc('debt')
                ->take(5),

            'active_clients' => Product::has('invoices')->count(),
            'avg_invoices_per_client' => round(Invoice::count() / max(Product::count(), 1), 2)
        ];
    }

    private function getFinancialReport()
    {
        return [
            'collections' => [
                'total' => Invoice::sum('Amount_collection'),
                'avg' => Invoice::avg('Amount_collection'),
                'max' => Invoice::max('Amount_collection')
            ],
            'commissions' => [
                'total' => Invoice::sum('Amount_Commission'),
                'discounts' => Invoice::sum('Discount_Commission'),
                'net' => Invoice::sum('Amount_Commission') - Invoice::sum('Discount_Commission')
            ],
            'vat' => [
                'total' => Invoice::sum('Value_VAT'),
                'rates' => Invoice::select('Rate_VAT')
                    ->selectRaw('COUNT(*) as count, SUM(Value_VAT) as total')
                    ->groupBy('Rate_VAT')
                    ->get()
            ],
            'attachments' => [
                'total_size' => InvoiceAttachments::sum(DB::raw('LENGTH(file_name)')),
                'per_invoice' => Invoice::withCount('attachments')->get()->avg('attachments_count')
            ]
        ];
    }

    private function getAttachmentAnalysis()
    {
        return [
            'total_attachments' => InvoiceAttachments::count(),
            'attachments_per_type' => InvoiceAttachments::select(DB::raw('SUBSTRING_INDEX(file_name, ".", -1) as extension'))
                ->selectRaw('COUNT(*) as count')
                ->groupBy('extension')
                ->get(),
            'recent_attachments' => InvoiceAttachments::with('invoices')
                ->latest()
                ->take(5)
                ->get()
                ->map(function ($attachment) {
                    return [
                        'file' => $attachment->file_name,
                        'invoice' => $attachment->invoice_id,
                        'uploader' => $attachment->Created_by,
                        'date' => $attachment->created_at
                    ];
                })
        ];
    }

    private function getTimeAnalysis()
    {
        return [
            'current_year' => $this->getYearlyStats(),
            'trends' => [
                'invoices' => $this->getMonthlyTrend('invoices'),
                'payments' => $this->getMonthlyTrend('payments')
            ]
        ];
    }

    private function getYearlyStats()
    {
        $currentYear = now()->year;

        return [
            'invoices' => Invoice::whereYear('invoice_date', $currentYear)->count(),
            'paid' => Invoice::whereYear('Payment_Date', $currentYear)->where('Value_Status', 1)->sum('Total'),
            'attachments' => InvoiceAttachments::whereYear('created_at', $currentYear)->count()
        ];
    }

    private function getMonthlyTrend($type)
    {
        $query = match ($type) {
            'invoices' => Invoice::query(),
            'payments' => Invoice::where('Value_Status', 1)
        };

        return $query->selectRaw('YEAR(invoice_date) as year, MONTH(invoice_date) as month, COUNT(*) as count, SUM(Total) as total')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
    }

    private function getTimeBasedStats($period)
    {
        $dates = [
            'week' => [now()->subWeek(), now()],
            'month' => [now()->subMonth(), now()]
        ];

        $results = Invoice::whereBetween('invoice_date', $dates[$period])
            ->selectRaw('Value_Status, Status, COUNT(*) as count, SUM(Total) as total')
            ->groupBy('Value_Status', 'Status')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item['Status'] => [
                    'count' => $item->count,
                    'amount' => $item->total
                ]];
            });

        // نضمن وجود جميع الحالات
        return collect([
            'مدفوعة' => ['count' => 0, 'amount' => 0],
            'غير مدفوعة' => ['count' => 0, 'amount' => 0],
            'مدفوعة جزئيا' => ['count' => 0, 'amount' => 0]
        ])->merge($results);
    }

    private function chartjs()
    {
        $invoices = Invoice::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->selectRaw('Value_Status, COUNT(*) as count')
            ->groupBy('Value_Status')
            ->pluck('count', 'Value_Status');

        $invoice = $invoices->sum();

        $culc_invoice_1 = $invoice ? ($invoices[1] ?? 0) / $invoice * 100 : 0;
        $culc_invoice_2 = $invoice ? ($invoices[2] ?? 0) / $invoice * 100 : 0;
        $culc_invoice_3 = $invoice ? ($invoices[3] ?? 0) / $invoice * 100 : 0;

        $chartjs =  app()->chartjs
            ->name('barChartTest')
            ->type('bar')
            ->labels(['مدفوعة', 'مدفوعة جزئياً', 'غير مدفوعة', 'الكل'])
            ->size(['width' => 400, 'height' => 200])
            ->datasets([

                [
                    "label" => "مدفوعة",
                    'backgroundColor' => ['rgba(25, 135, 84, 0.6)'],
                    'data' => [$culc_invoice_1]
                ],
                [
                    "label" => "مدفوعة جزئيا",
                    'backgroundColor' => ['rgba(255, 193, 7, 0.6)'],
                    'data' => [$culc_invoice_2]
                ],
                [
                    "label" => "غير مدفوعة",
                    'backgroundColor' =>  ['rgba(220, 53, 69, 0.6)'],
                    'data' => [$culc_invoice_3]
                ],
                [
                    "label" => "الكل",
                    'backgroundColor' => ['rgba(13, 110, 253, 0.6)'],
                    'data' => [100]
                ]
            ]);

        return $chartjs;
    }

    function getInitials($name)
    {
        $parts = explode(' ', trim($name));
        $count = count($parts);

        if ($count === 1) {
            return strtoupper(mb_substr($parts[0], 0, 1));
        } elseif ($count === 2) {
            return strtoupper(mb_substr($parts[0], 0, 1) . mb_substr($parts[1], 0, 1));
        } else {
            return strtoupper(mb_substr($parts[0], 0, 1) . mb_substr($parts[$count - 1], 0, 1));
        }

    }
}
