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
                            <form action="{{ route('force.reset', $user) }}" method="POST"
                                  class="row g-3 needs-validation" novalidate>
                                @csrf
                                @method('PUT')
                                <div class="card shadow rounded-4">
                                    <div class="card-body p-5">
                                        <div class="login-userheading">
                                            <h3>Force Reset</h3>
                                            <h4>You need to change your password as it is reset by the admin.</h4>
                                        </div>
                                        <div class="mb-3">
                                            <label for="current_password" class="form-label">Current Password</label>
                                            <input type="password" class="form-control pass-input"
                                                   name="current_password" id="current_password" required>
                                            <div class="invalid-feedback">Current password is required.</div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="new_password" class="form-label">New Password</label>
                                            <input type="password" class="form-control pass-input" name="new_password" id="new_password" required minlength="8">
                                            <div class="invalid-feedback">New password is required (min 8 characters).</div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                                            <input type="password" class="form-control pass-input" name="new_password_confirmation" id="new_password_confirmation" required>
                                            <div class="invalid-feedback">Please confirm the new password.</div>
                                        </div>

                                        <div class="form-login mt-4">
                                            <button type="submit" class="btn btn-primary w-100">Update Password</button>
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
