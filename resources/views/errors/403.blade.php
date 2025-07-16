@extends('errors::layout')

@section('title', __('Forbidden'))

@section('content')
    <div class="main-wrapper">
        <div class="error-box">
            <div class="error-img">
                <img src="{{ asset('assets/img/authentication/error-403.png') }}" class="img-fluid" alt="Img">
            </div>
            <h3 class="h2 mb-3">Error 403: Forbidden</h3>
            <p>
                Server has denied your request to access a particular resource or webpage.
            </p>
            <a href="javascript:history.back()" class="btn btn-primary">Back to Previous Page</a>
        </div>
    </div>
@endsection
