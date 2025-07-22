@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <div class="main-wrapper">
        <div class="account-content">
            <div class="login-wrapper login-new">
                <div class="row w-100">
                    <div class="col-lg-5 mx-auto">
                        <div class="login-content user-login">
                            <div class="login-logo animate__animated animate__bounce">
                                <img src="{{ asset('assets/img/logo.svg') }}" alt="img">
                                <a href="/login" class="login-logo logo-white">
                                    <img src="{{ asset('assets/img/logo-white.svg') }}"  alt="Img">
                                </a>
                            </div>
                            @if (session('status'))
                                <div class="alert alert-success">{{ session('status') }}</div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible w-100 fade show" role="alert">
                                    <strong>
                                        <i class="feather-alert-triangle flex-shrink-0 me-2"></i>
                                        There were some errors with your submission:
                                    </strong>
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                        <i class="fas fa-xmark"></i>
                                    </button>
                                </div>
                            @endif
                            <form action="{{ route('login') }}" method="POST" class="row g-3">
                                @csrf
                                <div class="card shadow rounded-4">
                                    <div class="card-body p-5">
                                        <div class="login-userheading">
                                            <h3>Welcome Back</h3>
                                            <h4>Sign in to your admin account.</h4>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Email <span class="text-danger"> *</span></label>
                                            <div class="input-group">
                                                <input type="text" name="email" value="" class="form-control border-end-0">
                                                <span class="input-group-text border-start-0">
                                                    <i class="ti ti-mail"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Password <span class="text-danger"> *</span></label>
                                            <div class="pass-group">
                                                <input name="password" type="password" class="pass-input form-control">
                                                <span class="border-start-0">
                                                    <i class="ti ti-eye-off toggle-password text-gray-9"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-login authentication-check">
                                            <div class="row">
                                                <div class="col-12 d-flex align-items-center justify-content-between">
                                                    <div class="custom-control custom-checkbox">
                                                        <label class="checkboxs ps-4 mb-0 pb-0 line-height-1 fs-16 text-gray-6">
                                                            <input type="checkbox" class="form-control">
                                                            <span class="checkmarks"></span>Remember Me
                                                        </label>
                                                    </div>
                                                    <div class="text-end">
                                                        <a class="text-orange fs-14 fw-medium" href="/forget-password">Forgot Password?</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-login">
                                            <button type="submit" class="btn btn-primary w-100">Sign In</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="my-4 d-flex justify-content-center align-items-center copyright-text">
                            <p>Copyright &copy; 2025 FlockSense</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
