@extends('layouts.auth')

@section('title', 'Email Verification')

@section('content')
    <div class="main-wrapper">
        <div class="account-content">
            <div class="login-wrapper login-new">
                <div class="row w-100">
                    <div class="col-lg-5 mx-auto">
                        <div class="login-content user-login">
                            <div class="login-logo">
                                <img src="{{ asset('assets/img/logo.svg') }}" alt="img">
                                <a href="/login" class="login-logo logo-white">
                                    <img src="{{ asset('assets/img/logo-white.svg') }}"  alt="Img">
                                </a>
                            </div>
                            <form action="{{ route('forget') }}" method="POST">
                                @csrf
                                <div class="card">
                                    <div class="card-body p-5">
                                        <div class="login-userheading">
                                            <h3>Email OTP Verification</h3>
                                            <h4>OTP sent to your Email Address ending ******doe@example.com.</h4>
                                        </div>

                                        <div class="text-center otp-input">
                                            <div class="d-flex align-items-center mb-3">
                                                <input type="text" class=" rounded w-100 py-sm-3 py-2 text-center fs-26 fw-bold me-3" id="digit-1" name="digit-1" data-next="digit-2" maxlength="1">
                                                <input type="text" class=" rounded w-100 py-sm-3 py-2 text-center fs-26 fw-bold me-3" id="digit-2" name="digit-2" data-next="digit-3" data-previous="digit-1" maxlength="1">
                                                <input type="text" class=" rounded w-100 py-sm-3 py-2 text-center fs-26 fw-bold me-3" id="digit-3" name="digit-3" data-next="digit-4" data-previous="digit-2" maxlength="1">
                                                <input type="text" class=" rounded w-100 py-sm-3 py-2 text-center fs-26 fw-bold me-3" id="digit-4" name="digit-4" data-next="digit-5" data-previous="digit-3" maxlength="1">
                                                <input type="text" class=" rounded w-100 py-sm-3 py-2 text-center fs-26 fw-bold me-3" id="digit-5" name="digit-5" data-next="digit-6" data-previous="digit-4" maxlength="1">
                                                <input type="text" class=" rounded w-100 py-sm-3 py-2 text-center fs-26 fw-bold" id="digit-6" name="digit-6" data-next="digit-6" data-previous="digit-5" maxlength="1">
                                            </div>
                                            <div>
                                                <div class="badge bg-danger-transparent mb-3">
                                                    <p class="d-flex align-items-center "><i class="ti ti-clock me-1"></i>09:59</p>
                                                </div>
                                                <div class="mb-3 d-flex justify-content-center">
                                                    <p class="text-gray-9">Didn't get the OTP? <a href="javascript:void(0);" class="text-primary">Resend OTP</a></p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-login">
                                            <button type="submit" class="btn btn-primary w-100">Verify & Proceed</button>
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
