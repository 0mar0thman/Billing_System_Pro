@extends('layouts.master')
@section('css')
    <!--- Internal Select2 css-->
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <!---Internal Fileupload css-->
    <link href="{{ URL::asset('assets/plugins/fileuploads/css/fileupload.css') }}" rel="stylesheet" type="text/css" />
    {{-- @vite('resources/css/invoice/sittings_invoices.css') --}}
    <link href="{{ URL::asset('assets/css/invoice/sittings_invoices.css') }}" rel="stylesheet" />
@endsection

@section('title') إعدادات الفاتورة @stop

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto">الفواتير</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ إعدادات الفاتورة</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    @if (session()->has('Add'))
        <div class="alert alert-success alert-dismissible fade show alert-position" role="alert">
            <strong>{{ session()->get('Add') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- row -->
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card settings-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-cog mr-2"></i> إعدادات الفاتورة
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('invoices_sittings_store') }}" method="post" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="Amount_collection" class="form-label">أعلى قيمة لمبلغ التحصيل لإلغاء الخصم</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="number" class="form-control" id="Amount_collection"
                                               name="Amount_collection" value="{{ old('Amount_collection', 0) }}"
                                               min="0" step="0.01" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="Amount_Commission" class="form-label">نسبة العمولة (%)</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="Amount_Commission"
                                               name="Amount_Commission" value="{{ old('Amount_Commission', 0) }}"
                                               min="0" max="100" step="0.01" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="Discount_Commission" class="form-label">نسبة الخصم (%)</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="Discount_Commission"
                                               name="Discount_Commission" value="{{ old('Discount_Commission', 0) }}"
                                               min="0" max="100" step="0.01" required
                                               oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                        <div class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-save">
                                <i class="fas fa-save mr-2"></i> حفظ الإعدادات
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
    <!--Internal Fileuploads js-->
    <script src="{{ URL::asset('assets/plugins/fileuploads/js/fileupload.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fileuploads/js/file-upload.js') }}"></script>

    <script>
        $(document).ready(function() {
            // إخفاء التنبيه بعد 5 ثواني
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);

            // تهيئة حقوق الإدخال
            $('input[type="number"]').on('focus', function() {
                $(this).select();
            });
        });
    </script>
@endsection
