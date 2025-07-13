@extends('layouts.error')

@section('title', __('Server Error'))

@section('content')
    <div class="main-wrapper">
        <div class="error-box">
            <div class="error-img">
                <img src="{{ asset('assets/img/authentication/error-500.png') }}" class="img-fluid" alt="Img">
            </div>
            <h3 class="h2 mb-3">Oops, something went wrong</h3>
            <p>
                Server Error 500. We apologise and are fixing the problem
                Please try again at a  later stage
            </p>
            <a href="javascript:history.back()" class="btn btn-primary">Back to Previous Page</a>
        </div>
    </div>
@endsection
