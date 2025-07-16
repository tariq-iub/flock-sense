@extends('errors::layout')

@section('title', __('Payment Required'))

@section('content')
    <div class="main-wrapper">
        <div class="error-box">
            <div class="error-img">
                <img src="{{ asset('assets/img/authentication/error-402.jpg') }}" class="img-fluid" alt="Img">
            </div>
            <h3 class="h2 mb-3">Payment Required</h3>
            <p>
                Payment is required if you want to access the requested resource.
            </p>
            <a href="javascript:history.back()" class="btn btn-primary">Back to Previous Page</a>
        </div>
    </div>
@endsection
