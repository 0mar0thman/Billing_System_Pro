@extends('layouts.master')
@section('title')
    تفاصيل الفاتورة
@stop
@section('css')
    <!-- Internal Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    {{-- @vite('resources/css/invoice/add_invoice.css') --}}
    <link href="{{ URL::asset('assets/css/invoice/add_invoice.css') }}" rel="stylesheet">

@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الفواتير</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تفاصيل الفاتورة</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <!-- row -->
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="panel panel-primary tabs-style-2">
                        <div class="tab-menu-heading">
                            <div class="tabs-menu1">
                                <ul class="nav panel-tabs main-nav-line">
                                    <li>
                                        <a href="#tab4" class="nav-link active" data-toggle="tab">
                                            <i class="fas fa-info-circle ml-1"></i> التفاصيل
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#tab6" class="nav-link" data-toggle="tab">
                                            <i class="fas fa-money-bill-wave ml-1"></i> حالات الدفع
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#tab5" class="nav-link" data-toggle="tab">
                                            <i class="fas fa-paperclip ml-1"></i> المرفقات
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="panel-body tabs-menu-body main-content-body-right border-top-0">
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab4">
                                    <div class="card card-invoice">
                                        <div class="invoice-header">
                                            <h1 class="invoice-title">فاتورة #{{ $invoice->id }}</h1>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="billed-to p-3 bg-light rounded">
                                                        <h6 class="font-weight-bold text-primary">معلومات العميل</h6>
                                                        <p class="mb-1"><strong>الاسم:</strong>
                                                            {{ $invoice->product_name }}</p>
                                                        <p class="mb-1"><strong>العنوان:</strong>
                                                            {{ $invoices_details_address->address ?? 'غير محدد' }}</p>
                                                        <p class="mb-1"><strong>الهاتف:</strong>
                                                            {{ $invoices_details_address->phone ?? 'غير محدد' }}</p>
                                                        <p class="mb-0"><strong>البريد الإلكتروني:</strong>
                                                            {{ $invoices_details_address->email ?? 'غير محدد' }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="invoice-info p-3 bg-light rounded">
                                                        <h6 class="font-weight-bold text-primary">معلومات الفاتورة</h6>
                                                        <p class="mb-1"><strong>تاريخ الفاتورة:</strong>
                                                            {{ $invoice->invoice_Date ?? 'غير محدد' }}</p>
                                                        <p class="mb-1"><strong>تاريخ الاستحقاق:</strong>
                                                            {{ $invoice->Due_date ?? 'غير محدد' }}</p>
                                                        <p class="mb-1"><strong>حالة الدفع:</strong>
                                                            @if ($invoice->Value_Status == 1)
                                                                <span class="badge badge-success">مدفوع</span>
                                                            @elseif ($invoice->Value_Status == 2)
                                                                <span class="badge badge-danger">غير مدفوع</span>
                                                            @elseif ($invoice->Value_Status == 3)
                                                                <span class="badge badge-warning">مدفوع جزئيًا</span>
                                                            @else
                                                                <span class="badge badge-secondary">غير محدد</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="table-responsive mt-4">
                                                <table class="table table-invoice">
                                                    <thead>
                                                        <tr>
                                                            <th class="wd-20p">البند</th>
                                                            <th class="wd-60p">القيمة</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>تاريخ الدفع</td>
                                                            <td>{{ $invoice->updated_at ?? 'غير محدد' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>اسم القسم</td>
                                                            <td>{{ $invoice->section?->section_name ?? 'غير محدد' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>مبلغ التحصيل</td>
                                                            <td>{{ number_format($invoice->Amount_collection ?? 0, 2) }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>مبلغ العمولة</td>
                                                            <td>{{ number_format($invoice->Amount_Commission ?? 0, 2) }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>الخصم</td>
                                                            <td>{{ number_format($invoice->Discount ?? 0, 2) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>نسبة الضريبة</td>
                                                            <td>{{ $invoice->Rate_VAT ?? 0 }}%</td>
                                                        </tr>
                                                        <tr>
                                                            <td>قيمة الضريبة</td>
                                                            <td>{{ number_format($invoice->Value_VAT ?? 0, 2) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="font-weight-bold text-primary">الإجمالي</td>
                                                            <td class="font-weight-bold text-primary">
                                                                {{ number_format($invoice->Total ?? 0, 2) }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="notes-section mt-4 p-3 bg-light rounded">
                                                <h6 class="font-weight-bold text-primary">ملاحظات</h6>
                                                <p>{{ $invoice->note ?? 'لا يوجد ملاحظات' }}</p>
                                            </div>

                                            <div class="d-flex justify-content-between mt-4">
                                                <div>
                                                    @if ($invoice->Value_Status != 1)
                                                        <a href="{{ URL::route('Status_show', [$invoice->id]) }}"
                                                            class="btn btn-primary-gr   adient">
                                                            <i class="fas fa-money-bill-wave ml-1"></i> دفع الآن
                                                        </a>
                                                    @endif
                                                </div>
                                                <div>
                                                    <a href="{{ route('invoices.print', $invoice->id) }}" target="_blank"
                                                        class="btn btn-print text-white">
                                                        <i class="fas fa-print ml-1"></i> طباعة الفاتورة
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane" id="tab6">
                                    <div class="card">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0">سجل حالات الدفع</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered w-100" id="payment-status-table">
                                                    <thead class="bg-light">
                                                        <tr>
                                                            <th>#</th>
                                                            <th>المنتج</th>
                                                            <th>القسم</th>
                                                            <th>الحالة</th>
                                                            <th>تاريخ الدفع</th>
                                                            <th>ملاحظات</th>
                                                            <th>المستخدم</th>
                                                            <th>تاريخ التحديث</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($invoices_details as $index => $detail)
                                                            <tr>
                                                                <td>{{ $index + 1 }}</td>
                                                                <td>{{ $detail->product_name ?? '-' }}</td>
                                                                <td>{{ $detail->section_name ?? '-' }}</td>
                                                                <td>
                                                                    @if ($detail->Value_Status == 1)
                                                                        <span
                                                                            class="badge badge-success">{{ $detail->Status ?? 'مدفوع' }}</span>
                                                                    @elseif($detail->Value_Status == 2)
                                                                        <span
                                                                            class="badge badge-danger">{{ $detail->Status ?? 'غير مدفوع' }}</span>
                                                                    @elseif($detail->Value_Status == 3)
                                                                        <span
                                                                            class="badge badge-warning">{{ $detail->Status ?? 'مدفوع جزئيًا' }}</span>
                                                                    @else
                                                                        <span class="badge badge-secondary">غير محدد</span>
                                                                    @endif
                                                                </td>
                                                                <td>{{ $detail->Payment_Date ?? 'غير محدد' }}</td>
                                                                <td>{{ $detail->note ?? '-' }}</td>
                                                                <td>{{ $detail->user ?? '-' }}</td>
                                                                <td>{{ $detail->updated_at ?? '-' }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane" id="tab5">
                                    <div class="card">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0">مرفقات الفاتورة</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="file-upload-container mb-4">
                                                <p class="text-danger">* صيغة المرفق pdf, jpeg, .jpg, png</p>
                                                <h5 class="card-title">إضافة مرفقات</h5>
                                                <form method="post" action="{{ route('attachments.store') }}"
                                                    enctype="multipart/form-data" class="mt-3">
                                                    @csrf
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" id="customFile"
                                                            name="file_name" required>
                                                        <input type="hidden" name="invoice_id"
                                                            value="{{ $invoice->id }}">
                                                        <label class="custom-file-label" for="customFile">اختر
                                                            الملف</label>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary-gradient mt-3"
                                                        name="uploadedFile">
                                                        <i class="fas fa-upload ml-1"></i> رفع الملف
                                                    </button>
                                                </form>
                                            </div>

                                            <div class="table-responsive">
                                                <table class="table table-bordered w-100" id="attachments-table">
                                                    <thead class="bg-light">
                                                        <tr>
                                                            <th>#</th>
                                                            <th>اسم الملف</th>
                                                            <th>تاريخ الإنشاء</th>
                                                            <th>بواسطة</th>
                                                            <th class="text-center">العمليات</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($attachments as $index => $attachment)
                                                            <tr>
                                                                <td>{{ $index + 1 }}</td>
                                                                <td>
                                                                    <a href="{{ url('Attachments/' . $attachment->invoice_number . '/' . $attachment->file_name) }}"
                                                                        target="_blank">
                                                                        {{ $attachment->file_name }}
                                                                    </a>
                                                                </td>
                                                                <td>{{ $attachment->created_at }}</td>
                                                                <td>{{ $attachment->Created_by }}</td>
                                                                <td class="d-flex justify-content-center"
                                                                    style="gap: 0.5rem;">
                                                                    <a href="{{ url('Attachments/' . $attachment->invoice_number . '/' . $attachment->file_name) }}"
                                                                        target="_blank" class="btn btn-sm btn-info">
                                                                        <i class="fas fa-eye"></i> عرض
                                                                    </a>
                                                                    <a href="{{ url('Attachments/' . $attachment->invoice_number . '/' . $attachment->file_name) }}"
                                                                        download class="btn btn-sm btn-primary">
                                                                        <i class="fas fa-download"></i> تحميل
                                                                    </a>
                                                                    @can('حذف المرفق')
                                                                        <form
                                                                            action="{{ route('attachments.destroy', $attachment->id) }}"
                                                                            method="POST" style="display: inline;">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit"
                                                                                class="btn btn-sm btn-danger"
                                                                                onclick="return confirm('هل أنت متأكد من حذف هذا المرفق؟')">
                                                                                <i class="fas fa-trash"></i> حذف
                                                                            </button>
                                                                        </form>
                                                                    @endcan
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- row closed -->
@endsection

@section('js')
    <!-- Internal Data tables -->
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>

    <!-- Internal Select2 js-->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    {{-- @vite('resources/js/invoice/details_invoices.js') --}}
    <script src="{{ URL::asset('assets/js/invoice/details_invoices.js') }}"></script>

@endsection
