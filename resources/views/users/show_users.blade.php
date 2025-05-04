@extends('layouts.master')
@section('css')
    <!--Internal   Notify -->
    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    <style>
        .breadcrumb-header {
            background-color: white;
            border-radius: 12px;
            padding: 1rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
        }
    </style>
@section('title')
    قائمة المستخدمين
@stop

@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">المستخدمين</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0"> /
                قائمة المستخدمين</span>
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection
@section('content')

@if (session()->has('Add'))
    <script>
        window.onload = function() {
            notif({
                msg: " تم اضافة الصلاحية بنجاح",
                type: "success"
            });
        }
    </script>
@endif

@if (session()->has('edit'))
    <script>
        window.onload = function() {
            notif({
                msg: " تم تحديث بيانات الصلاحية بنجاح",
                type: "success"
            });
        }
    </script>
@endif

@if (session()->has('delete'))
    <script>
        window.onload = function() {
            notif({
                msg: " تم حذف الصلاحية بنجاح",
                type: "error"
            });
        }
    </script>
@endif

<!-- row -->
<div class="row row-sm">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between">
                    <div class="col-lg-12 margin-tb">
                        <div class="pull-right">
                            @can('اضافة صلاحية')
                                <a class="btn btn-primary btn-sm p-2" href="{{ route('users.create') }}"> <i
                                        class="fas fa-save ml-1"></i> اضافة</a>
                            @endcan
                        </div>
                    </div>
                    <br>
                </div>

            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example1" class="table table-bordered text-center align-middle ">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>اسم المستخدم</th>
                                <th>البريد الإلكتروني</th>
                                <th>الحالة</th>
                                <th>الدور</th>
                                <th>تاريخ الإنشاء</th>
                                <th>إجراءات</th>
                            </tr>
                        </thead>
                        </style>
                        <tbody>
                            @php $i = 0; @endphp
                            @foreach ($roles as $role)
                                @foreach ($role->users as $user)
                                    <tr class="align-middle">
                                        <td class="text-center">{{ ++$i }}</td>
                                        <td class="text-center align-middle">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div>
                                                    <h6 class="mb-0">{{ $user->name }}</h6>
                                                    <small class="text-muted">ID: {{ $user->id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="mailto:{{ $user->email }}" class="text-primary">
                                                {{ $user->email }}
                                            </a>
                                        </td>
                                        <td>
                                            @if ($user->status === 'مفعل')
                                                <span class="badge bg-success rounded-pill p-2">
                                                    <i class="fas fa-check-circle me-1"></i> مفعل
                                                </span>
                                            @else
                                                <span class="badge bg-secondary rounded-pill p-2">
                                                    <i class="fas fa-times-circle me-1"></i> غير مفعل
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($role->name == 'admin')
                                                <span class="badge bg-info text-dark fs-6">
                                                    <i class="fas fa-shield-alt me-1"></i> {{ $role->name }}
                                                </span>
                                            @elseif($role->name == 'owner')
                                                <span class="badge bg-warning text-dark fs-6">
                                                    <i class="fas fa-crown me-1"></i> {{ $role->name }}
                                                </span>
                                            @else
                                                <span class="badge bg-light text-dark fs-6">
                                                    <i class="fas fa-user me-1"></i> {{ $role->name }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-nowrap">
                                            <i class="far fa-calendar-alt me-1 text-muted"></i>
                                            {{ $user->created_at->format('Y-m-d') }}
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                @can('تعديل مستخدم')
                                                    <a class="btn btn-sm btn-outline-primary ml-1"
                                                        href="{{ route('users.edit', $user->id) }}" title="تعديل">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endcan
                                                @can('حذف مستخدم')
                                                    @if ($role->name !== 'owner' && $role->name !== 'admin')
                                                        <form action="{{ route('users.destroy', $user->id) }}"
                                                            method="POST" class="d-inline ms-3">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                                title="حذف"
                                                                onclick="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--/div-->
</div>
<!-- row closed -->
</div>
<!-- Container closed -->
</div>
<!-- main-content closed -->
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
<!--Internal  Datatable js -->
<script src="{{ URL::asset('assets/js/table-data.js') }}"></script>
<!--Internal  Notify js -->
<script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/notify/js/notifit-custom.js') }}"></script>
@endsection
