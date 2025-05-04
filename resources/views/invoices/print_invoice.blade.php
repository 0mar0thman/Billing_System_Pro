<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طباعة فاتورة {{ $invoice->invoice_number }}</title>
    {{-- @vite('resources/css/invoice/print_invoice.css') --}}
    <link href="{{ URL::asset('assets/css/invoice/print_invoice.css') }}" rel="stylesheet" />
</head>

<body>
    <div class="invoice-container">
        <div class="card-invoice">
            <div class="invoice-header">
                <h1 class="invoice-title">فاتورة ضريبية</h1>
                <p class="invoice-number">رقم الفاتورة: {{ $invoice->invoice_number }}</p>
            </div>

            <div class="info-section">
                <div class="info-box">
                    <label>معلومات العميل</label>
                    <h6>{{ $invoice->product }}</h6>
                    <p>
                        @if ($invoice->address)
                            <strong>العنوان:</strong> {{ $invoice->address }}<br>
                        @endif
                        @if ($invoice->phone)
                            <strong>الهاتف:</strong> {{ $invoice->phone }}<br>
                        @endif
                        @if ($invoice->email)
                            <strong>البريد الإلكتروني:</strong> {{ $invoice->email }}
                        @endif
                    </p>
                </div>
                <div class="info-box">
                    <label>معلومات الفاتورة</label>
                    <div class="invoice-info-row">
                        <span>تاريخ الفاتورة:</span>
                        <span>{{ $invoice->invoice_Date ? date('Y-m-d', strtotime($invoice->invoice_Date)) : 'غير محدد' }}</span>
                    </div>
                    <div class="invoice-info-row">
                        <span>تاريخ الاستحقاق:</span>
                        <span>{{ $invoice->Due_date ? date('Y-m-d', strtotime($invoice->Due_date)) : 'غير محدد' }}</span>
                    </div>
                    <div class="invoice-info-row">
                        <span>حالة الدفع:</span>
                        <span>
                            @if ($invoice->Value_Status == 1)
                                <span class="badge bg-success">مدفوع</span>
                            @elseif ($invoice->Value_Status == 2)
                                <span class="badge bg-danger">غير مدفوع</span>
                            @elseif ($invoice->Value_Status == 3)
                                <span class="badge bg-warning">مدفوع جزئيًا</span>
                            @else
                                <span class="badge bg-secondary">غير محدد</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <table class="table-invoice">
                <thead>
                    <tr>
                        <th>البند</th>
                        <th>القيمة</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>تاريخ الدفع</td>
                        <td>{{ $invoice->Payment_Date ? date('Y-m-d', strtotime($invoice->Payment_Date)) : 'غير محدد' }}
                        </td>
                    </tr>
                    <tr>
                        <td>اسم البنك</td>
                        <td>{{ $invoice->section?->section_name ?? 'غير محدد' }}</td>
                    </tr>
                    <tr>
                        <td>مبلغ التحصيل</td>
                        <td>{{ number_format($invoice->Amount_collection, 2) }} ر.س</td>
                    </tr>
                    <tr>
                        <td>مبلغ العمولة</td>
                        <td>{{ number_format($invoice->Amount_Commission, 2) }} ر.س</td>
                    </tr>
                    <tr>
                        <td>الخصم</td>
                        <td>{{ number_format($invoice->Discount, 2) }} ر.س</td>
                    </tr>
                    <tr>
                        <td>نسبة الضريبة</td>
                        <td>{{ $invoice->Rate_VAT }}%</td>
                    </tr>
                    <tr>
                        <td>قيمة الضريبة</td>
                        <td>{{ number_format($invoice->Value_VAT, 2) }} ر.س</td>
                    </tr>
                    <tr>
                        <td class="tx-bold">الإجمالي شامل الضريبة</td>
                        <td class="tx-bold tx-primary">{{ number_format($invoice->Total, 2) }} ر.س</td>
                    </tr>
                </tbody>
            </table>

            <div class="qr-code no-print">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode("فاتورة رقم: $invoice->invoice_number\nالمبلغ: {$invoice->Total} ر.س\nالتاريخ: $invoice->invoice_Date") }}"
                    alt="QR Code للفاتورة">
                <p>مسح للتحقق من صحة الفاتورة</p>
            </div>

            <div class="invoice-notes">
                <label class="main-content-label">ملاحظات</label>
                <p>{{ $invoice->note ?: 'لا توجد ملاحظات' }}</p>
            </div>

            <div class="actions no-print">
                <button class="btn btn-print" onclick="window.print()">
                    <i class="fas fa-print"></i> طباعة الفاتورة
                </button>
                <button class="btn btn-close" onclick="window.close()">
                    <i class="fas fa-times"></i> إغلاق
                </button>
            </div>
        </div>
    </div>

    <script>
        window.onload = function() {
            // Auto-print after 500ms if not already printed
            setTimeout(function() {
                if (!window.matchMedia || !window.matchMedia('print').matches) {
                    window.print();
                }
            }, 500);

            // Close window after printing if supported
            window.onafterprint = function() {
                setTimeout(function() {
                    window.close();
                }, 500);
            };
        };
    </script>
</body>

</html>
