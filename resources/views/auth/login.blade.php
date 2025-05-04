@extends('layouts.master2')

@section('title')
    تسجيل دخول
@stop

@section('css')
    <!-- Sidemenu-respoansive-tabs css -->
    <link href="{{ URL::asset('assets/plugins/sidemenu-responsive-tabs/css/sidemenu-responsive-tabs.css') }}"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::asset('assets/css/auth/login.css') }}">

@endsection

@section('content')
    <div class="container-fluid">
        <div class="row no-gutter">
            <!-- The content half -->
            <div class="col-md-6 col-lg-6 col-xl-12 bg-white">
                <div class="login d-flex align-items-center py-2">
                    <!-- Demo content-->
                    <div class="container p-0">
                        <div class="row">
                            <div class="col-md-10 col-lg-10 col-xl-6 mx-auto">
                                <div class="card-sigin">
                                    <div class="main-signup-header">
                                        <h2>مرحبا بك</h2>
                                        <h5 class="font-weight-semibold mb-4">تسجيل الدخول</h5>
                                        <form method="POST" action="{{ route('login') }}">
                                            @csrf
                                            <div class="form-group">
                                                <label>البريد الالكتروني</label>
                                                <input id="email" type="email"
                                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                                    value="{{ old('email') }}" required autocomplete="email" autofocus>
                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label>كلمة المرور</label>
                                                <input id="password" type="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    name="password" required autocomplete="current-password">
                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="remember"
                                                        id="remember" {{ old('remember') ? 'checked' : '' }}>
                                                    <label class="form-check-label mr-4" for="remember">
                                                        تذكرني
                                                    </label>
                                                </div>
                                            </div>

                                            <button type="submit" class="btn btn-main-primary">
                                                {{ __('تسجيل الدخول') }}
                                            </button>

                                            {{-- <a href="{{ route('register') }}" class="btn btn-main-primary mt-3">
                                                {{ __('انشاء حساب') }}
                                            </a> --}}
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End -->
                </div>
            </div><!-- End -->
        </div>
    </div>
@endsection

@section('js')
@endsection
