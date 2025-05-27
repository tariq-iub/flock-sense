@extends('layouts.empty')

@section('title', 'Login')

@section('content')
    <div class="login">
        <div class="login-content">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <h1 class="text-center">Sign In</h1>
                <div class="text-muted text-center mb-4">
                    For your protection, please verify your identity.
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input id="email" type="email" class="form-control form-control-lg fs-15px @error('email') is-invalid @enderror"
                           name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="mb-3">

                    <div class="d-flex">
                        <label for="password" class="form-label">Password</label>
                        <a href="{{ route('password.request') }}" class="ms-auto text-muted">Forgot password?</a>
                    </div>
                    <input id="password" type="password" class="form-control form-control-lg fs-15px @error('password') is-invalid @enderror"
                           name="password" required autocomplete="current-password">
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label fw-500" for="remember">Remember me</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-theme btn-lg d-block w-100 fw-500 mb-3">Sign In</button>
            </form>
        </div>
    </div>
@endsection
