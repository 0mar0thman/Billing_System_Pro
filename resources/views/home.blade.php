@extends('layouts.master')
@section('title')
    الرئيسية
@stop
@section('css')
    <!--  Owl-carousel css-->
    <link href="{{ URL::asset('assets/plugins/owl-carousel/owl.carousel.css') }}" rel="stylesheet" />
    <!-- Maps css -->
    <link href="{{ URL::asset('assets/plugins/jqvmap/jqvmap.min.css') }}" rel="stylesheet">
    <style>
        .bank-debt-header {
            display: flex;
            justify-content: space-between;
            padding: 8px 15px;
            background-color: #f5f5f5;
            font-weight: bold;
            border-bottom: 1px solid #eee;
        }

        .bank-debt-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 15px;
            border-bottom: 1px solid #eee;
        }

        .bank-debt-summary {
            padding: 8px 15px;
            background-color: #f9f9f9;
            font-size: 12px;
            display: flex;
            justify-content: space-between;
        }

        .vmap-wrapper {
            height: auto !important;
        }
    </style>
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="left-content">
            <div>
                <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1"><strong>مرحبًا بك في لوحة التحكم</strong></h2>
                <p class="mg-b-0 mt-3">نظام إدارة الفواتير والديون بين البنوك والعملاء</p>
            </div>
        </div>
        <div class="main-dashboard-header-right">
            <div>
                <label class="tx-13 mb-2"><strong>إجمالي الديون</strong></label>
                <div class="main-star">
                    <h5>{{ number_format($invoiceStatus['statuses']['غير مدفوعة']['amount'] ?? 0, 2) }} ج</h5>
                </div>
            </div>
            <div>
                <label class="tx-13 mb-2"><strong>الفواتير المدفوعة</strong></label>
                <h5>{{ $invoiceStatus['statuses']['مدفوعة']['count'] ?? 0 }}</h5>
            </div>
            <div>
                <label class="tx-13 mb-2"><strong>العملاء النشطين</strong></label>
                <h5>{{ $stats['total_clients'] }}</h5>
            </div>
        </div>
    </div>
    <!-- /breadcrumb -->
@endsection
@section('content')
    <!-- row -->
    <div class="row row-sm">
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden sales-card bg-primary-gradient">
                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                    <div class="">
                        <h5 class="mb-3 tx-15 text-white"><strong>إجمالي الفواتير</strong></h5>
                    </div>
                    <div class="pb-0 mt-0">
                        <div class="d-flex">
                            <div class="">
                                <h4 class="tx-24 font-weight-bold mb-1 text-white">
                                    {{-- @if (isset($invoiceStatus['weekly']['مدفوعة']['amount'])) --}}
                                        {{ number_format(
                                            $invoiceStatus['weekly']['مدفوعة']['amount'] +
                                                $invoiceStatus['weekly']['غير مدفوعة']['amount'] +
                                                $invoiceStatus['weekly']['مدفوعة جزئيا']['amount'] ??
                                                0,
                                            2,
                                        ) }}
                                    {{-- @endif --}}
                                    ج
                                </h4>
                                <h6 class="mb-3 tx-12 text-white">
                                    <span class="op-7">(آخر أسبوع)</span>
                                    <i class="fas fa-arrow-circle-up text-white"></i>
                                    <span class="text-white ">
                                        {{ $stats['total_invoices_week'] }} فاتورة
                                    </span>
                                </h6>

                                <h4 class="tx-22 font-weight-bold mb-1 text-white">
                                    {{ number_format(
                                        $invoiceStatus['monthly']['مدفوعة']['amount'] +
                                            $invoiceStatus['monthly']['غير مدفوعة']['amount'] +
                                            $invoiceStatus['monthly']['مدفوعة جزئيا']['amount'] ??
                                            0,
                                        2,
                                    ) }}
                                    ج </h4>
                                <h6 class="mb-3 tx-12 text-white">
                                    <span class="op-7">(آخر شهر)</span>
                                    <i class="fas fa-arrow-circle-up text-white"></i>
                                    <span class="text-white ">
                                        {{ $stats['total_invoices_month'] }} فاتورة
                                    </span>
                                </h6>

                                <h4 class="tx-20 font-weight-bold mb-1 text-white">
                                    {{ number_format($invoiceStatus['all'], 2) }} ج
                                </h4>
                                <h6 class="mb-3 tx-12 text-white">
                                    <span class="op-7">(الكل)</span>
                                    <i class="fas fa-arrow-circle-up text-white"></i>
                                    <span class="text-white ">
                                        {{ $stats['total_invoices'] }} فاتورة
                                    </span>
                                </h6>
                            </div>
                            <span an class="float-right my-auto mr-auto">
                                {{-- <div class="mb-3">
                                    <i class="fas fa-arrow-circle-up text-white"></i>
                                    <span class="text-white op-7">
                                        {{ $stats['total_invoices'] }} فاتورة
                                    </span>
                                </div> --}}
                            </span>
                        </div>
                    </div>
                </div>
                <span id="compositeline" class="pt-1">5,9,5,6,4,12,18,14,10,15,12,5,8,5,12,5,12,10,16,12</span>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden sales-card bg-danger-gradient">
                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                    <div class="">
                        <h6 class="mb-3 tx-15 text-white"><strong>الفواتير غير المدفوعة</strong></h6>
                    </div>
                    <div class="pb-0 mt-0">
                        <div class="d-flex">
                            <div>
                                <h4 class="tx-24 font-weight-bold mb-1 text-white">
                                    {{ number_format($invoiceStatus['weekly']['غير مدفوعة']['amount'] ?? 0, 2) }} ج
                                </h4>
                                <h6 class="mb-3 tx-12 text-white">
                                    <span class="op-7">(آخر أسبوع)</span>
                                    <i class="fas fa-arrow-circle-up text-white"></i>
                                    <span class="text-white">
                                        {{ $stats['total_invoices_week_2'] }} فاتورة
                                    </span>
                                </h6>
                                <p class="mb-0 tx-14 text-white op-7">

                                <h4 class="tx-22 font-weight-bold mb-1 text-white">
                                    {{ number_format($invoiceStatus['monthly']['غير مدفوعة']['amount'] ?? 0, 2) }} ج
                                </h4>
                                <h6 class="mb-3 tx-12 text-white">
                                    <span class="op-7">(آخر شهر)</span>
                                    <i class="fas fa-arrow-circle-up text-white"></i>
                                    <span class="text-white">
                                        {{ $stats['total_invoices_month_2'] }} فاتورة
                                    </span>
                                </h6>

                                <h4 class="tx-20 font-weight-bold mb-1 text-white">
                                    {{ number_format($invoiceStatus['all_2'] ?? 0, 2) }} ج
                                </h4>
                                <h6 class="mb-3 tx-12 text-white">
                                    <span class="op-7">(الكل)</span>
                                    <i class="fas fa-arrow-circle-up text-white"></i>
                                    <span class="text-white ">
                                        {{ $stats['total_invoices_2'] }} فاتورة
                                    </span>
                                </h6>
                                </p>
                            </div>
                            <span class="float-right my-auto mr-auto">
                                {{-- <i class="fas fa-arrow-circle-down text-white"></i>
                                <span class="text-white op-7">
                                    {{ $invoiceStatus['statuses']['غير مدفوعة']['count'] ?? 0 }} فاتورة
                                </span> --}}
                            </span>
                        </div>
                    </div>
                </div>
                <span id="compositeline2" class="pt-1">3,2,4,6,12,14,8,7,14,16,12,7,8,4,3,2,2,5,6,7</span>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden sales-card bg-success-gradient">
                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                    <div class="">
                        <h6 class="mb-3 tx-15 text-white"><strong>الفواتير المدفوعة</strong></h6>
                    </div>
                    <div class="pb-0 mt-0">
                        <div class="d-flex">
                            <div>
                                <h4 class="tx-24 font-weight-bold mb-1 text-white">
                                    {{ number_format($invoiceStatus['weekly']['مدفوعة']['amount'] ?? 0, 2) }} ج
                                </h4>
                                <h6 class="mb-3 tx-12 text-white">
                                    <span class="op-7">(آخر أسبوع)</span>
                                    <i class="fas fa-arrow-circle-up text-white"></i>
                                    <span class="text-white ">
                                        {{ $stats['total_invoices_week_1'] }} فاتورة
                                    </span>
                                </h6>
                                <p class="mb-0 tx-14 text-white op-7">

                                <h4 class="tx-22 font-weight-bold mb-1 text-white">
                                    {{ number_format($invoiceStatus['monthly']['مدفوعة']['amount'] ?? 0, 2) }} ج
                                </h4>
                                <h6 class="mb-3 tx-12 text-white">
                                    <span class="op-7">(آخر شهر)</span>
                                    <i class="fas fa-arrow-circle-up text-white"></i>
                                    <span class="text-white ">
                                        {{ $stats['total_invoices_month_1'] }} فاتورة
                                    </span>
                                </h6>

                                <h4 class="tx-20 font-weight-bold mb-1 text-white">
                                    {{ number_format($invoiceStatus['all_1'] ?? 0, 2) }} ج
                                </h4>
                                <h6 class="mb-3 tx-12 text-white">
                                    <span class="op-7">(الكل)</span>
                                    <i class="fas fa-arrow-circle-up text-white"></i>
                                    <span class="text-white ">
                                        {{ $stats['total_invoices_1'] }} فاتورة
                                    </span>
                                </h6>
                                </p>
                            </div>
                            <span class="float-right my-auto mr-auto">
                                {{-- <i class="fas fa-arrow-circle-up text-white"></i>
                                <span class="text-white op-7">
                                    {{ $invoiceStatus['statuses']['مدفوعة']['count'] ?? 0 }} فاتورة
                                </span> --}}
                            </span>
                        </div>
                    </div>
                </div>
                <span id="compositeline3" class="pt-1">5,10,5,20,22,12,15,18,20,15,8,12,22,5,10,12,22,15,16,10</span>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden sales-card bg-warning-gradient">
                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                    <div class="">
                        <h6 class="mb-3 tx-15 text-white"><strong>الفواتير المدفوعة جزئياً</strong></h6>
                    </div>
                    <div class="pb-0 mt-0">
                        <div class="d-flex">
                            <div>
                                <h4 class="tx-24 font-weight-bold mb-1 text-white">
                                    {{ number_format($invoiceStatus['weekly']['مدفوع جزئيا']['amount'] ?? 0, 2) }} ج
                                </h4>
                                <h6 class="mb-3 tx-12 text-white">
                                    <span class="op-7">(آخر أسبوع)</span>
                                    <i class="fas fa-arrow-circle-up text-white"></i>
                                    <span class="text-white ">
                                        {{ $stats['total_invoices_week_3'] }} فاتورة
                                    </span>
                                </h6>
                                <p class="mb-0 tx-14 text-white op-7">

                                <h4 class="tx-22 font-weight-bold mb-1 text-white">
                                    {{ number_format($invoiceStatus['monthly']['مدفوع جزئيا']['amount'] ?? 0, 2) }} ج
                                </h4>
                                <h6 class="mb-3 tx-12 text-white">
                                    <span class="op-7">(آخر شهر)</span>
                                    <i class="fas fa-arrow-circle-up text-white"></i>
                                    <span class="text-white ">
                                        {{ $stats['total_invoices_month_3'] }} فاتورة
                                    </span>
                                </h6>

                                <h4 class="tx-20 font-weight-bold mb-1 text-white">
                                    {{ number_format($invoiceStatus['all_3'] ?? 0, 2) }} ج
                                </h4>
                                <h6 class="mb-3 tx-12 text-white">
                                    <span class="op-7">(الكل)</span>
                                    <i class="fas fa-arrow-circle-up text-white"></i>
                                    <span class="text-white ">
                                        {{ $stats['total_invoices_3'] }} فاتورة
                                    </span>
                                </h6>
                                </p>
                            </div>
                            <span class="float-right my-auto mr-auto">
                                {{-- <i class="fas fa-arrow-circle-down text-white"></i>
                                <span class="text-white op-7">
                                    {{ $invoiceStatus['statuses']['مدفوع جزئيا']['count'] ?? 0 }} فاتورة
                                </span> --}}
                            </span>
                        </div>
                    </div>
                </div>
                <span id="compositeline4" class="pt-1">5,9,5,6,4,12,18,14,10,15,12,5,8,5,12,5,12,10,16,12</span>
            </div>
        </div>
    </div>
    <!-- row closed -->

    <!-- row opened -->
    <div class="row row-sm">
        <div class="col-md-12 col-lg-12 col-xl-7">
            <div class="card">
                <div class="card-header bg-transparent pd-b-0 pd-t-20 bd-b-0 col-xl-5">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-0">حالة الفواتير</h4>
                    </div>
                    <p class="tx-12 text-muted mt-2">إحصائيات تفصيلية عن حالة الفواتير</p>
                </div>

                <div class="col-xl-12 col-md-12 col-lg-6 mt-2">
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <td>
                                    <div style="width: 100%; max-width: 600px;">
                                        {!! $chartjs->render() !!}
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12 col-xl-5">
            <div class="card card-dashboard-map-one">
                <label class="main-content-label">توزيع الديون على البنوك</label>
                <span class="d-block mg-b-20 text-muted tx-12">أعلى البنوك من حيث قيمة الديون غير المسددة</span>
                <div class="vmap-wrapper ht-180">
                    <div class="bank-debt-header">
                        <span>اسم البنك</span>
                        <span>قيمة الدين</span>
                    </div>
                    @foreach ($bankAnalysis->take(7) as $bank)
                        <div class="bank-debt-item">
                            <span class="bank-name">{{ $bank['name'] }}</span>
                            <span class="debt-amount">{{ number_format($bank['debt'], 2) }} ج</span>
                        </div>
                    @endforeach
                    @if ($bankAnalysis->isNotEmpty())
                        <div class="bank-debt-summary">
                            <span>أعلى بنك مدين:</span>
                            <span class="text-danger">{{ $bankAnalysis->first()['name'] }}
                                ({{ number_format($bankAnalysis->first()['debt'], 2) }} ج)</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- row closed -->

    <!-- row opened -->
    <div class="row row-sm">
        <div class="col-xl-4 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header pb-1">
                    <h3 class="card-title mb-2">العملاء الأكثر مديونية</h3>
                    <p class="tx-12 mb-0 text-muted">اعلي 5 عملاء لديهم أعلى قيمة ديون غير مسددة</p>
                </div>
                <div class="card-body p-0 customers mt-1">
                    <div class="list-group list-lg-group list-group-flush">
                        @foreach ($clientAnalysis['top_debtors'] as $client)
                            <div class="list-group-item list-group-item-action">
                                <div class="media mt-0">
                                    <div
                                        class="avatar-lg rounded-circle bg-danger text-white d-flex align-items-center justify-content-center">
                                        {{ substr($client['name'], 0, 1) }}
                                    </div>
                                    <div class="media-body mr-2">
                                        <div class="d-flex align-items-center">
                                            <div class="mt-0">
                                                <h5 class="mb-1 tx-15">{{ $client['name'] }}</h5>
                                                <p class="mb-0 tx-13 text-muted">دين:
                                                    {{ number_format($client['debt'], 2) }} ج</p>
                                                <p class="mb-0 tx-12 text-muted">البنك: {{ $client['section'] }}</p>
                                            </div>
                                            <span class="mr-auto wd-45p fs-16 mt-2">
                                                <div id="spark{{ $loop->index }}" class="wd-100p"></div>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-12 col-lg-6">
            <div class="card">
                <div class="card-header pb-1">
                    <h3 class="card-title mb-2">النشاط المالي</h3>
                    <p class="tx-12 mb-0 text-muted">ملخص شامل للنشاط المالي للنظام</p>
                </div>
                <div class="product-timeline card-body pt-2 mt-1">
                    <ul class="timeline-1 mb-0">
                        <li class="mt-0">
                            <i class="ti-pie-chart bg-primary-gradient text-white product-icon"></i>
                            <span class="font-weight-semibold mb-4 tx-14">إجمالي التحصيل</span>
                            <p class="mb-0 text-muted tx-12">
                                {{ number_format($financialReport['collections']['total'], 2) }} ج</p>
                        </li>
                        <li class="mt-0">
                            <i class="mdi mdi-cart-outline bg-danger-gradient text-white product-icon"></i>
                            <span class="font-weight-semibold mb-4 tx-14">إجمالي العمولات</span>
                            <p class="mb-0 text-muted tx-12">
                                {{ number_format($financialReport['commissions']['total'], 2) }} ج</p>
                        </li>
                        <li class="mt-0">
                            <i class="ti-bar-chart-alt bg-success-gradient text-white product-icon"></i>
                            <span class="font-weight-semibold mb-4 tx-14">إجمالي الضرائب</span>
                            <p class="mb-0 text-muted tx-12">{{ number_format($financialReport['vat']['total'], 2) }} ج
                            </p>
                        </li>
                        <li class="mt-0">
                            <i class="ti-wallet bg-warning-gradient text-white product-icon"></i>
                            <span class="font-weight-semibold mb-4 tx-14">متوسط قيمة الفاتورة</span>
                            <p class="mb-0 text-muted tx-12">
                                {{ number_format($financialReport['collections']['avg'], 2) }} ج</p>
                        </li>
                        <li class="mt-0">
                            <i class="icon-note icons bg-info-gradient text-white product-icon"></i>
                            <span class="font-weight-semibold mb-4 tx-14">أعلى قيمة فاتورة</span>
                            <p class="mb-0 text-muted tx-12">
                                {{ number_format($financialReport['collections']['max'], 2) }} ج</p>
                        </li>
                        <li class="mt-0">
                            <i class="si si-wallet bg-purple-gradient text-white product-icon"></i>
                            <span class="font-weight-semibold mb-4 tx-14">إجمالي الخصومات</span>
                            <p class="mb-0 text-muted tx-12">
                                {{ number_format($financialReport['commissions']['discounts'], 2) }} ج</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-12 col-lg-6">
            <div class="card">
                <div class="card-header pb-0">
                    <h3 class="card-title mb-2">آخر الفواتير المدفوعة</h3>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach (\App\Models\Invoice::where('Value_Status', 1)->latest()->take(5)->get() as $invoice)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="tx-medium">#{{ $invoice->id }}</span>
                                    <span
                                        class="tx-12 d-block">{{ $invoice->products->product_name ?? 'غير محدد' }}</span>
                                </div>
                                <span class="badge badge-success">{{ number_format($invoice->Total, 2) }} ج</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row row-sm">
        <div class="col-xl-12 col-md-12 col-lg-6">
            <div class="card">
                <div class="card-header pb-0">
                    <h3 class="card-title mb-2">أحدث الفواتير</h3>
                    <p class="tx-12 mb-3 text-muted">آخر 5 فواتير تم إضافتها للنظام</p>
                </div>
                <div class="card-body sales-info ot-0 pt-0 pb-2">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th class="wd-25p">التاريخ</th>
                                    <th class="wd-35p">العميل</th>
                                    <th class="wd-25p tx-right">المبلغ</th>
                                    <th class="wd-15p">الحالة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (\App\Models\Invoice::latest()->take(5)->get() as $invoice)
                                    <tr>
                                        <td class="tx-12">{{ optional($invoice->created_at)->format('d/m/Y') }}</td>
                                        <td class="tx-medium">{{ $invoice->products->product_name ?? 'غير محدد' }}</td>
                                        <td class="text-right">{{ number_format($invoice->Total, 2) }} ج</td>
                                        <td>
                                            @if ($invoice->Value_Status == 1)
                                                <span class="badge badge-success">مدفوعة</span>
                                            @elseif($invoice->Value_Status == 2)
                                                <span class="badge badge-danger">غير مدفوعة</span>
                                            @else
                                                <span class="badge badge-warning">جزئية</span>
                                            @endif
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
    <!-- row close -->
@endsection

@section('js')
    <!--Internal  Chart.bundle js -->
    <script src="{{ URL::asset('assets/plugins/chart.js/Chart.bundle.min.js') }}"></script>
    <!-- Moment js -->
    <script src="{{ URL::asset('assets/plugins/raphael/raphael.min.js') }}"></script>
    <!--Internal  Flot js-->
    <script src="{{ URL::asset('assets/plugins/jquery.flot/jquery.flot.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/jquery.flot/jquery.flot.pie.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/jquery.flot/jquery.flot.resize.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/jquery.flot/jquery.flot.categories.js') }}"></script>
    <script src="{{ URL::asset('assets/js/dashboard.sampledata.js') }}"></script>
    <script src="{{ URL::asset('assets/js/chart.flot.sampledata.js') }}"></script>
    <!--Internal Apexchart js-->
    <script src="{{ URL::asset('assets/js/apexcharts.js') }}"></script>
    <!-- Internal Map -->
    <script src="{{ URL::asset('assets/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
    <script src="{{ URL::asset('assets/js/modal-popup.js') }}"></script>
    <!--Internal  index js -->
    <script src="{{ URL::asset('assets/js/index.js') }}"></script>
    <script src="{{ URL::asset('assets/js/jquery.vmap.sampledata.js') }}"></script>
@endsection
