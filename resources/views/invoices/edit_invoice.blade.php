@extends('layouts.master')
@section('css')
    <!--- Internal Select2 css-->
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    {{-- @vite('resources/css/invoice/edit_invoice.css') --}}
    <link href="{{ URL::asset('assets/css/invoice/edit_invoice.css') }}" rel="stylesheet">
@endsection
@section('title')
    تعديل فاتورة
@stop

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto">الفواتير</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تعديل فاتورة</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    @if (session()->has('edit'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('edit') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- row -->
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3>تعديل فاتورة #{{ $invoices->id }}</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('invoices.update', $invoices->id) }}" autocomplete="off">
                        @csrf
                        @method('PUT')

                        <!-- Section 1: Dates -->
                        <div class="form-section">
                            <h6 class="form-section-title">معلومات التاريخ</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>تاريخ الفاتورة</label>
                                        <input class="form-control fc-datepicker" name="invoice_Date"
                                            placeholder="YYYY-MM-DD" type="text" value="{{ $invoices->invoice_Date }}"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>تاريخ الاستحقاق</label>
                                        <input class="form-control fc-datepicker" name="Due_date" placeholder="YYYY-MM-DD"
                                            type="text" value="{{ $invoices->Due_date }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Basic Info -->
                        <div class="form-section">
                            <h6 class="form-section-title">معلومات الفاتورة الأساسية</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">اسم البنك</label>
                                        <select name="Section" class="form-control SlectBox" id="Section" disabled>
                                            <option value="{{ $invoices->sections->id }}" selected>
                                                {{ $invoices->sections->section_name }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">اسم العميل</label>
                                        <select id="product" name="product" class="form-control">
                                            @foreach ($products as $product)
                                                <option value="{{ $product->product_name }}"
                                                    {{ $product->product_name == $invoices->product_name ? 'selected' : '' }}>
                                                    {{ $product->product_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">مبلغ التحصيل</label>
                                        <input type="text" class="form-control" id="Amount_collection"
                                            name="Amount_collection" oninput="calculateCommissionAndVAT()"
                                            value="{{ $invoices->Amount_collection }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 3: Calculations -->
                        <div class="form-section">
                            <h6 class="form-section-title">الحسابات المالية</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">مبلغ العمولة</label>
                                        <input type="text" class="form-control bg-light" id="Amount_Commission"
                                            name="Amount_Commission" value="{{ $invoices->Amount_Commission }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">الخصم</label>
                                        <input type="text" class="form-control bg-light" id="Discount" name="Discount"
                                            value="{{ $invoices->Discount }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">نسبة ضريبة القيمة المضافة</label>
                                        <select name="Rate_VAT" id="Rate_VAT" class="form-control"
                                            onchange="calculateCommissionAndVAT()">
                                            <option value="{{ $invoices->Rate_VAT }}" selected>{{ $invoices->Rate_VAT }}
                                            </option>
                                            @if ($invoices->Rate_VAT == 10)
                                                <option value="5%">5%</option>
                                            @else($invoices->Rate_VAT == 5)
                                                <option value="10%">10%</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 4: Totals -->
                        <div class="form-section">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">قيمة ضريبة القيمة المضافة</label>
                                        <input type="text" class="form-control bg-light" id="Value_VAT"
                                            name="Value_VAT" value="{{ $invoices->Value_VAT }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">الاجمالي شامل الضريبة</label>
                                        <input type="text" class="form-control bg-light" id="Total"
                                            name="Total" value="{{ $invoices->Total }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 5: Notes -->
                        <div class="form-section">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleTextarea">ملاحظات</label>
                                        <textarea class="form-control" id="exampleTextarea" name="note" rows="3">{{ $invoices->note ?? 'لا يوجد ملاحظات' }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-center mt-4">
                            <button type="submit" class="btn btn-primary-gradient" id="submitBtn">
                                <i class="fas fa-save ml-1"></i> حفظ التعديلات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- Internal Select2 js-->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <!--Internal  Datepicker js -->
    <script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
    {{-- @vite('resources/js/invoice/edit_invoice.js') --}}
    <script src="{{ URL::asset('assets/js/invoice/edit_invoice.js') }}"></script>
@endsection
