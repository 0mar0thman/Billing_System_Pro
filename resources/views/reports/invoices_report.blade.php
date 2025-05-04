@extends('layouts.master')
@section('css')
    <!-- Internal Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.css') }}" rel="stylesheet">
    <style>
        .breadcrumb-header {
            background-color: white;
            border-radius: 12px;
            padding: 1rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
        }

        .alert {
            border-radius: 10px;
        }

        #invoice_number_section {
            display: none;
        }
    </style>
@endsection

@section('title', 'تقرير الفواتير - مورا سوفت للادارة الفواتير')

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">التقارير</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تقرير الفواتير</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>خطأ</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-xl-12">
            <div class="card mg-b-20">
                <div class="card-header pb-0">
                    <form action="{{ route('invoices.report.search') }}" method="POST" autocomplete="off">
                        @csrf
                        <div class="row">
                            <div class="col-lg-3">
                                <label class="rdiobox">
                                    <input name="rdio" type="radio" value="1"
                                        {{ $searchType == 1 ? 'checked' : '' }} id="type_div">
                                    <span>بحث بنوع الفاتورة</span>
                                </label>
                            </div>
                            <div class="col-lg-3">
                                <label class="rdiobox">
                                    <input name="rdio" type="radio" value="2"
                                        {{ $searchType == 2 ? 'checked' : '' }}>
                                    <span>بحث برقم الفاتورة</span>
                                </label>
                            </div>
                        </div>
                        <br><br>

                        <div class="row">
                            <div class="col-lg-3 mg-t-20 mg-lg-t-0" id="type_section">
                                <p class="mg-b-10">تحديد نوع الفواتير</p>
                                <select class="form-control select2" name="type" required>
                                    <option value="{{ $type }}" selected>{{ $type }}</option>
                                    <option value="الكل">الكل</option>
                                    <option value="مدفوعة">مدفوعة</option>
                                    <option value="غير مدفوعة">غير مدفوعة</option>
                                    <option value="مدفوعة جزئيا">مدفوعة جزئيا</option>
                                </select>
                            </div>

                            <div class="col-lg-3 mg-t-20 mg-lg-t-0" id="invoice_number_section">
                                <p class="mg-b-10">البحث برقم الفاتورة</p>
                                <input type="text" class="form-control" name="invoice_number"
                                    value="{{ $invoiceNumber }}" placeholder="أدخل رقم الفاتورة">
                            </div>

                            <div class="col-lg-3" id="start_at_section">
                                <label>من تاريخ</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                    </div>
                                    <input class="form-control fc-datepicker" name="start_at" value="{{ $startAt }}"
                                        placeholder="YYYY-MM-DD" type="text">
                                </div>
                            </div>

                            <div class="col-lg-3" id="end_at_section">
                                <label>إلى تاريخ</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                    </div>
                                    <input class="form-control fc-datepicker" name="end_at" value="{{ $endAt }}"
                                        placeholder="YYYY-MM-DD" type="text">
                                </div>
                            </div>
                        </div>
                        <br>

                        <div class="row">
                            <div class="col-sm-1 col-md-1">
                                <button type="submit" class="btn btn-primary btn-block">بحث</button>
                            </div>
                            <div class="col-sm-1 col-md-1">
                                <a href="{{ route('invoices.report') }}" class="btn btn-secondary btn-block">إعادة
                                    تعيين</a>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        @if (isset($invoices) && $invoices->count() > 0)
                            <table id="invoices-table" class="table table-hover table-bordered table-striped">
                                <thead class="bg-primary text-white">
                                    <tr>
                                        {{-- <th>#</th> --}}
                                        <th>رقم الفاتورة</th>
                                        <th>التاريخ</th>
                                        <th>تاريخ الاستحقاق</th>
                                        <th>المنتج</th>
                                        <th>القسم</th>
                                        <th>الخصم</th>
                                        <th>الضريبة</th>
                                        <th>الإجمالي</th>
                                        <th>الحالة</th>
                                        <th>ملاحظات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoices as $invoice)
                                        <tr>
                                            <td>{{ $invoice->id }}</td>
                                            {{-- <td>{{ $invoice->invoice_number }}</td> --}}
                                            <td>{{ $invoice->invoice_Date }}</td>
                                            <td>{{ $invoice->Due_date }}</td>
                                            <td>
                                                <a href="{{ route('invoices.details', $invoice->id) }}">
                                                    {{ $invoice->product_name }}
                                                </a>
                                            </td>
                                            <td>{{ $invoice->sections->section_name }}</td>
                                            <td>{{ number_format($invoice->Discount_commition, 2) }}</td>
                                            <td>{{ number_format($invoice->Value_VAT, 2) }} ({{ $invoice->Rate_VAT }}%)
                                            </td>
                                            <td>{{ number_format($invoice->Total, 2) }}</td>
                                            <td>
                                                @if ($invoice->Value_Status == 1)
                                                    <span class="badge badge-success">{{ $invoice->Status }}</span>
                                                @elseif($invoice->Value_Status == 2)
                                                    <span class="badge badge-danger">{{ $invoice->Status }}</span>
                                                @else
                                                    <span class="badge badge-warning">{{ $invoice->Status }}</span>
                                                @endif
                                            </td>
                                            <td>{{ Str::limit($invoice->note, 30) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            @if ($searchType == 2 && request()->isMethod('post'))
                                <div class="alert alert-danger">
                                    <strong>خطأ!</strong> لم يتم العثور على فاتورة بالرقم: {{ $invoiceNumber }}
                                </div>
                            @elseif(request()->isMethod('post'))
                                <div class="alert alert-info">
                                    لا توجد فواتير متطابقة مع معايير البحث
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
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
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
    <!--Internal  Datatable js -->
    <script src="{{ URL::asset('assets/js/table-data.js') }}"></script>

    <!--Internal  Datepicker js -->
    <script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
    <!--Internal  jquery.maskedinput js -->
    <script src="{{ URL::asset('assets/plugins/jquery.maskedinput/jquery.maskedinput.js') }}"></script>
    <!--Internal  spectrum-colorpicker js -->
    <script src="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.js') }}"></script>
    <!-- Internal Select2.min js -->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // إظهار/إخفاء الأقسام حسب نوع البحث
            function toggleSearchSections() {
                if ($('input[name="rdio"]:checked').val() == '1') {
                    $('#type_section').show();
                    $('#invoice_number_section').hide();
                    $('#start_at_section').show();
                    $('#end_at_section').show();
                } else {
                    $('#type_section').hide();
                    $('#invoice_number_section').show();
                    $('#start_at_section').hide();
                    $('#end_at_section').hide();
                }
            }

            // تنفيذ عند التحميل
            toggleSearchSections();

            // تنفيذ عند تغيير نوع البحث
            $('input[name="rdio"]').change(function() {
                toggleSearchSections();
            });

            // تهيئة تاريخ الاختيار
            $('.fc-datepicker').datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true,
                regional: "ar"
            });

            // تهيئة select2
            $('.select2').select2({
                placeholder: 'اختر نوع الفاتورة',
                allowClear: true
            });

            // تهيئة جدول البيانات
            $('#invoices-table').DataTable({
                responsive: true,
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.25/i18n/Arabic.json"
                },
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
        });
    </script>
@endsection
