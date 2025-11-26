@extends('layouts.app')

@section('title', 'Comming Soon')

@section('content')
    <div class="comming-soon-pg w-100">
        <div class="coming-soon-box">
            <span>This Module is</span>
            <h1><span> COMING </span> SOON </h1>
            <p class="mb-5">Please check back later, We are working hard to get everything just right.</p>

            <ul class="social-media-icons">
                <li><a href="javascript:void(0);"><i class="fab fa-facebook-f"></i></a></li>
                <li><a href="javascript:void(0);"><i class="fab fa-instagram"></i></a></li>
                <li><a href="javascript:void(0);"><i class="fab fa-twitter"></i></a></li>
                <li><a href="javascript:void(0);"><i class="fab fa-pinterest-p"></i></a></li>
                <li><a href="javascript:void(0);"><i class="fab fa-linkedin"></i></a></li>
            </ul>
        </div>
    </div>
@endsection

@push('js')

@endpush
