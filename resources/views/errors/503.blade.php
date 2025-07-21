@extends('errors::layout')

@section('title', __('Service Unavailable'))

@section('content')
    <div class="main-wrapper">
        <div class="error-box">
            <div class="error-img">
                <img src="{{ asset('assets/img/authentication/error-5xx.png') }}" class="img-fluid" alt="Img">
            </div>
            <h3 class="h2 mb-3">Error 503: Service Unavailable</h3>
            <p>
                Web server is temporarily unable to handle the request due to a high volume of traffic, maintenance, or other internal issues.
            </p>
            <a href="javascript:history.back()" class="btn btn-primary">Back to Previous Page</a>
        </div>
    </div>
@endsection
