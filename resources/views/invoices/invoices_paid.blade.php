@extends('layouts.master')
@section('title') الفواتير المدفوعة @stop
@section('css')
    <!-- Internal Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <!--Internal   Notify -->
    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    {{-- @vite('resources/css/invoice/invoices.css') --}}
    <link href="{{ URL::asset('assets/css/invoice/invoices.css') }}" rel="stylesheet" />
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto">الفواتير</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ الفواتير المدفوعة</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    @if (session()->has('delete_invoice'))
        <div class="alert alert-success">
            تم حذف الفاتورة بنجاح
        </div>
    @endif

    @if (session()->has('Status_Update'))
        <div class="alert alert-success">
            تم تحديث حالة الدفع بنجاح
        </div>
    @endif

    <!-- row -->
    <div class="row">
        <!--div-->
        <div class="col-xl-12">
            <div class="card mg-b-20">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div class="d-flex gap-3 flex-wrap">
                            @can('اضافة فاتورة')
                            <a class="btn btn-warning flex-shrink-0 " href="{{ route('invoices.create') }}">
                                <h5><strong>اضافة فاتورة</strong></h5>
                            </a>
                        @endcan
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="paid-invoices-table" class="table table-hover" data-page-length='50'>
                            <thead class="bg-blue-50">
                                <tr>
                                    <th class="border-bottom-0">#</th>
                                    <th class="border-bottom-0">تاريخ الفاتورة</th>
                                    <th class="border-bottom-0">تاريخ الاستحقاق</th>
                                    <th class="border-bottom-0">المنتج</th>
                                    <th class="border-bottom-0">القسم</th>
                                    <th class="border-bottom-0">الخصم</th>
                                    <th class="border-bottom-0">قيمة الضريبة</th>
                                    <th class="border-bottom-0">الاجمالي</th>
                                    <th class="border-bottom-0">الحالة</th>
                                    <th class="border-bottom-0">الاستعلامات</th>
                                    <th class="border-bottom-0">العمليات</th>
                                    <th class="border-bottom-0">ملاحظات</th>
                                    <th class="border-bottom-0">نسبة الضريبة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 0; @endphp
                                @foreach ($invoices as $invoice)
                                    @if($invoice->Value_Status == 1)
                                        @php $i++; @endphp
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>{{ $invoice->invoice_Date }}</td>
                                            <td>{{ $invoice->Due_date }}</td>
                                            <td>{{ $invoice->product_name }}</td>
                                            <td>{{ $invoice->sections->section_name }}</td>
                                            <td>{{ $invoice->Discount_Commission }}</td>
                                            <td>{{ $invoice->Value_VAT }}</td>
                                            <td>{{ $invoice->Total }}</td>
                                            <td>
                                                <span class="badge badge-success">مدفوع</span>
                                            </td>
                                            <td>
                                                <a href="{{ url('InvoicesDetails') }}/{{ $invoice->id }}"
                                                   class="btn btn-sm btn-info">
                                                   <i class="fas fa-search ml-1"></i> استعلام
                                                </a>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-primary btn-sm dropdown-toggle"
                                                            type="button"
                                                            data-toggle="dropdown">
                                                        العمليات <i class="fas fa-caret-down ml-1"></i>
                                                    </button>
                                                    <div class="dropdown-menu tx-13">
                                                        <a class="dropdown-item"
                                                           href="{{ url('edit_invoice') }}/{{ $invoice->id }}">
                                                           <i class="fas fa-edit text-primary mr-1"></i> تعديل
                                                        </a>
                                                        <a class="dropdown-item"
                                                           href="#"
                                                           data-invoice_id="{{ $invoice->id }}"
                                                           data-toggle="modal"
                                                           data-target="#delete_invoice">
                                                           <i class="fas fa-trash-alt text-danger mr-1"></i> حذف
                                                        </a>
                                                        <a class="dropdown-item"
                                                           href="{{ URL::route('Status_show', [$invoice->id]) }}">
                                                           <i class="fas fa-money-bill text-success mr-1"></i> تغيير الحالة
                                                        </a>
                                                        <a class="dropdown-item"
                                                           href="#"
                                                           data-invoice_id="{{ $invoice->id }}"
                                                           data-toggle="modal"
                                                           data-target="#Transfer_invoice">
                                                           <i class="fas fa-archive text-warning mr-1"></i> أرشفة
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $invoice->Rate_VAT }}%</td>
                                            <td>{{ $invoice->note }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--/div-->
    </div>

    <!-- حذف الفاتورة -->
    <div class="modal fade" id="delete_invoice" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">حذف الفاتورة</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('invoices.destroy', 'test') }}" method="post">
                    @csrf
                    @method('delete')
                    <div class="modal-body">
                        <p>هل أنت متأكد من عملية الحذف؟</p>
                        <input type="hidden" name="invoice_id" id="invoice_id" value="">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-danger">تأكيد</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ارشيف الفاتورة -->
    <div class="modal fade" id="Transfer_invoice" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">أرشفة الفاتورة</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('invoices.destroy', 'test') }}" method="post">
                    @csrf
                    @method('delete')
                    <div class="modal-body">
                        <p>هل أنت متأكد من عملية الأرشفة؟</p>
                        <input type="hidden" name="invoice_id" id="invoice_id" value="">
                        <input type="hidden" name="id_page" id="id_page" value="2">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-success">تأكيد</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- Internal Data tables -->
      <!-- Internal Data tables -->
      <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
      <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
      <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
      <!--Internal  Notify js -->
      <script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
      <script src="{{ URL::asset('assets/plugins/notify/js/notifit-custom.js') }}"></script>
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

    <script>
        $(document).ready(function() {
            // تهيئة جدول الفواتير
            $('#paid-invoices-table').DataTable({
                responsive: true,
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.25/i18n/Arabic.json"
                },
                dom: 'Bfrtip',
                buttons: [
                    'excel', 'print'
                ]
            });

            // إعداد مودال الحذف
            $('#delete_invoice').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var invoice_id = button.data('invoice_id');
                $(this).find('.modal-body #invoice_id').val(invoice_id);
            });

            // إعداد مودال الأرشفة
            $('#Transfer_invoice').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var invoice_id = button.data('invoice_id');
                $(this).find('.modal-body #invoice_id').val(invoice_id);
            });
        });
    </script>
@endsection
