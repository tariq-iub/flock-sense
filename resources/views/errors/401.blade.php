@extends('errors::layout')

@section('title', __('Unauthorized'))

@section('content')
    <div class="main-wrapper">
        <div class="error-box">
            <div class="error-img">
                <img src="{{ asset('assets/img/authentication/error-401.png') }}" class="img-fluid" alt="Img">
            </div>
            <h3 class="h2 mb-3">Unauthorized Access</h3>
            <p>
                You do not have permission to access this resource. This error, indicated by the HTTP status code 401,
                typically occurs when your login credentials (username and password) are incorrect or when you don't have
                the necessary permissions to access the requested resource.
            </p>
            <a href="javascript:history.back()" class="btn btn-primary">Back to Previous Page</a>
        </div>
    </div>
@endsection
