@extends('layouts.master')
@section('css')
    <!--- Internal Select2 css-->
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <!---Internal Fileupload css-->
    <link href="{{ URL::asset('assets/plugins/fileuploads/css/fileupload.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/css/add_invoice.css') }}" rel="stylesheet" type="text/css" />
    <!-- Flatpickr for modern datepicker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    {{-- @vite('resources/css/invoice/add_invoice.css') --}}
    <link rel="stylesheet" href="{{ URL::asset('assets/css/invoice/add_invoice.css') }}">
@endsection

@section('title') اضافة فاتورة @stop

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الفواتير</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                    اضافة فاتورة</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <h3 class="mr-2">إنشاء فاتورة جديدة
                        </h3>
                    </h5>
                </div>
                <div class="card-body">
                    @if (session('Add'))
                        <div class="alert alert-success" role="alert">
                            {{ session('Add') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h5 class="alert-heading"><i class="fas fa-exclamation-triangle"></i> يوجد أخطاء في المدخلات
                            </h5>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form id="invoice-form" action="{{ route('invoices.store') }}" method="post"
                        enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        <input name="invoice_id" type="hidden" value="{{ $invoices + 1 ?? 0 }}">
                        <!-- معلومات الفاتورة الأساسية -->
                        <div class="form-section">
                            <div class="form-section-title">

                                <i class="fas fa-info-circle"></i> <span class="mr-2">المعلومات الأساسية</span>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="invoice_Date" class="form-label">تاريخ الاصدار</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                            </div>
                                            <input class="form-control" id="invoice_Date" name="invoice_Date"
                                                placeholder="اختر التاريخ" type="text"
                                                value="{{ old('invoice_Date', date('Y-m-d')) }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="Due_date" class="form-label">تاريخ الاستحقاق</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                                            </div>
                                            <input class="form-control" id="Due_date" name="Due_date"
                                                placeholder="اختر التاريخ" type="text"
                                                value="{{ old('Due_date', date('Y-m-d', strtotime('+3 months'))) }}"
                                                required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- معلومات البنك والعميل -->
                        <div class="form-section">
                            <div class="form-section-title">
                                <i class="fas fa-building"></i><span class="mr-2">معلومات البنك والعميل</span>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="section_id" class="form-label">البنك</label>
                                        <select name="section_id" id="section_id" class="form-control select2"
                                            style="height: 50px; font-size: 18px;">
                                            <option value="" selected disabled>اختر البنك من القائمة</option>
                                            @foreach ($sections as $section)
                                                <option value="{{ $section->id }}">{{ $section->section_name }}</option>
                                            @endforeach
                                        </select>

                                        <small class="form-text text-muted">اختر البنك الذي تتعامل معه</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="product_id" class="form-label">العميل</label>
                                        <select id="product_id" name="product_id" class="form-control select2">
                                            <option value="" selected disabled>اختر العميل بعد تحديد البنك</option>
                                        </select>
                                        <small class="form-text text-muted">سيتم تحميل العملاء بعد اختيار البنك</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- الحسابات المالية -->
                        <div class="form-section">
                            <div class="form-section-title">
                                <i class="fas fa-calculator"></i> <span class="mr-2">الحسابات المالية</span>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-4">
                                    <div class="form-group">
                                        <label for="Amount_collection" class="form-label">مبلغ التحصيل (الهدف
                                            {{ $SittingsInvoices->Amount_collection ?? 0 }})</label>
                                        <input type="hidden" name="Amount_collection_value" id="Amount_collection_value"
                                            value="{{ $SittingsInvoices->Amount_collection ?? 0 }}">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="number" class="form-control" id="Amount_collection"
                                                name="Amount_collection" value="{{ old('Amount_collection') }}"
                                                oninput="calculateCommissionAndVAT()" required min="0"
                                                step="0.01" placeholder="أدخل مبلغ التحصيل">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="calculation-box">
                                <h5><i class="fas fa-percentage mr-2"></i><span class="mr-2">تفاصيل الحسابات</span></h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="Amount_Commission" class="form-label">مبلغ العمولة
                                                ({{ $SittingsInvoices->Amount_Commission ?? 0 }}%)</label>
                                            <input type="hidden" name="Amount_Commission_value"
                                                value="{{ $SittingsInvoices->Amount_Commission ?? 0 }}">
                                            <input type="number" class="form-control readonly-input"
                                                id="Amount_Commission" name="Amount_Commission"
                                                value="{{ old('Amount_Commission', number_format(0, 2)) }}" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="Discount" class="form-label">خصم العمولة
                                                ({{ $SittingsInvoices->Discount_Commission ?? 0 }}%)</label>
                                            <input type="hidden" name="Discount_Commission_value"
                                                value="{{ $SittingsInvoices->Discount_Commission ?? 0 }}">
                                            <input type="number" class="form-control readonly-input" id="Discount"
                                                name="Discount_Commission"
                                                value="{{ old('Discount_Commission', number_format(0, 2)) }}" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="Rate_VAT" class="form-label">
                                                <i class="fas fa-percentage"></i> نسبة الضريبة على العمولة
                                            </label>
                                            <div class="input-group">
                                                <select name="Rate_VAT" id="Rate_VAT"
                                                    class="form-control select2-with-input" required>
                                                    <option value="" disabled
                                                        {{ old('Rate_VAT') ? '' : 'selected' }}>اختر النسبة</option>
                                                    <option value="5" {{ old('Rate_VAT') == '5' ? 'selected' : '' }}>
                                                        5%</option>
                                                    <option value="10"
                                                        {{ old('Rate_VAT') == '10' ? 'selected' : '' }}>10%</option>
                                                </select>
                                                <div class="input-group-append">
                                                    <span class="input-group-text bg-primary text-white">%</span>
                                                </div>
                                            </div>
                                            <small class="form-text text-muted">تطبق على صافي العمولة بعد الخصم</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="Value_VAT" class="form-label">قيمة ضريبة القيمة المضافة</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                                <input type="number" class="form-control readonly-input" id="Value_VAT"
                                                    name="Value_VAT" value="{{ old('Value_VAT', number_format(0, 2)) }}"
                                                    readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="Total" class="form-label">الاجمالي شامل الضريبة</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                                <input type="number" class="form-control readonly-input" id="Total"
                                                    name="Total" value="{{ old('Total', number_format(0, 2)) }}"
                                                    readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-block mb-2">حالة الدفع</label>
                            <div class="p-3 border rounded d-flex gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="Value_Status" id="Status_1"
                                        value="1" {{ old('Value_Status') == '1' ? 'selected' : '' }}>
                                    <label class="form-check-label mr-3" for="Status_1">
                                        مدفوع
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="Value_Status" id="Status_2"
                                        value="2" {{ old('Value_Status') == '2' ? 'selected' : '' }}>
                                    <label class="form-check-label mr-3" for="Status_2">
                                        مدفوع جزئيا
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="Value_Status" id="Status_3"
                                        value="3" {{ old('Value_Status') == '3' ? 'selected' : '' }}>
                                    <label class="form-check-label mr-3" for="Status_3">
                                        غير مدفوع
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- حقول العميل المخفية -->
                        <input type="hidden" class="form-control" id="product_name" name="product_name"
                            value="{{ old('product_name') }}">
                        <input type="hidden" id="email" name="email" value="{{ old('email') }}">
                        <input type="hidden" id="address" name="address" value="{{ old('address') }}">
                        <input type="hidden" id="phone" name="phone" value="{{ old('phone') }}">

                        <!-- الملاحظات والمرفقات -->
                        <div class="form-section">
                            <div class="form-section-title">
                                <i class="fas fa-paperclip"></i><span class="mr-2">الملاحظات والمرفقات</span>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="note" class="form-label">ملاحظات</label>
                                        <textarea class="form-control" id="note" name="note" rows="3"
                                            placeholder="أدخل أي ملاحظات إضافية هنا...">{{ old('note') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="file-upload-container transition-all hover-scale">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <h5>اسحب وأفلت الملف هنا أو انقر للاختيار</h5>
                                        <p class="text-muted">يمكنك رفع ملفات بصيغة PDF, JPG, JPEG, PNG</p>
                                        <input type="file" name="pic" class="d-none" id="pic"
                                            accept=".pdf,.jpg,.jpeg,.png,image/jpeg,image/png" />
                                        <label for="pic" class="btn btn-outline-primary mt-3">
                                            <i class="fas fa-folder-open mr-2"></i><span class="mr-2">اختر ملف</span>
                                        </label>
                                        <div id="file-name" class="mt-2 text-muted"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- زر الحفظ -->
                        <div class="row mt-5">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary-gradient btn-lg hover-scale transition-all">
                                    <i class="fas fa-save mr-2"></i> حفظ الفاتورة
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="add_invoice" data-sections='@json($sections)'></div>
@endsection

@section('js')
    <!-- Internal Select2 js-->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <!-- Flatpickr for datepicker -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ar.js"></script>
    <!-- Font Awesome for icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
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
    <!--Internal  Notify js -->
    <script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/notify/js/notifit-custom.js') }}"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        window.amountCommissionRate = {{ $SittingsInvoices->Amount_Commission ?? 0 }};
        window.discountRate = {{ $SittingsInvoices->Discount_Commission ?? 0 }};
        window.sectionProducts = @json($sections->load('products'));
    </script>
    {{-- @vite('resources/js/invoice/add_invoice_cluc.js')
    @vite('resources/js/invoice/add_invoice.js') --}}
    <script src="{{ URL::asset('assets/js/invoice/add_invoice_cluc.js') }}"></script>
    <script src="{{ URL::asset('assets/js/invoice/add_invoice.js') }}"></script>
@endsection
