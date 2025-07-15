@extends('layouts.app')

@section('title', 'Create User')

@section('content')
    <div class="content pb-0">
        <div class="row">
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="bg-light rounded p-3 mb-4">
                            <div class="text-center mb-3">
                                <a href="javascript:void(0);" class="avatar avatar-xl online avatar-rounded">
                                    @php
                                        $firstMedia = $user->media()->orderBy('order_column')->first();
                                        $path = asset("assets/img/user.jpg");
                                        if ($firstMedia && \File::exists(public_path($firstMedia->file_path))) {
                                            $path = asset($firstMedia->file_path);
                                        }
                                    @endphp
                                    <img src="{{ $path }}" alt="Img">
                                </a>
                                <h5 class="mb-1"><a href="javascript:void(0);">{{ $user->name }} </a></h5>
                                <p class="fs-12">{{ $user->email }}</p>
                            </div>
                            <div class="row g-1">
                                <div class="col-sm-4">
                                    <div class="rounded bg-white text-center py-1">
                                        <h4 class="mb-1">{{ $user->farms_count }}</h4>
                                        <p class="fs-12">Farms</p>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="rounded bg-white text-center py-1">
                                        <h4 class="mb-1">{{ $user->sheds_count }}</h4>
                                        <p class="fs-12">Sheds</p>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="rounded bg-white text-center py-1">
                                        <h4 class="mb-1">{{ ($user->birds_count / 1000) }}K</h4>
                                        <p class="fs-12">Birds</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <a href="javascript:void(0);" class="btn btn-primary d-inline-flex align-items-center justify-content-center w-100">
                                <i class="ti ti-circle-plus me-2"></i>Add Farm
                            </a>
                        </div>
                        <div class="list-group" id="farmList">
                            @foreach($user->farms as $farm)
                                <a href="javascript:void(0)" class="list-group-item list-group-item-action farm-item"
                                   data-farm-id="{{ $farm->id }}">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="fs-14 mb-1">{{ $farm->name }}</h5>
                                        <small class="text-body-secondary">{{ $farm->sheds_count }} Sheds</small>
                                    </div>
                                    <small class="text-body-secondary">{{ $farm->address }}</small>
                                </a>
                            @endforeach
                        </div>
                        <hr>
                        <div>
                            <div class="mb-2">
                                <h5>User Settings</h5>
                            </div>
                            <div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between">
                                        Email Notification
                                        <div class="form-check form-check-md form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                   id="email-{{ $user->settings->id }}" {{ $user->settings->notifications_email ? 'checked' : '' }}>
                                        </div>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        SMS Notification
                                        <div class="form-check form-check-md form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                   id="sms-{{ $user->settings->id }}" {{ $user->settings->notifications_sms ? 'checked' : '' }}>
                                        </div>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        Timezone
                                        <span>{{ $user->settings->timezone }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-8" id="farmDetailsContainer">

            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(function(){
            $('#farmList').on('click', '.farm-item', function() {
                var farmId = $(this).data('farm-id');
                // Optionally, show a loading spinner
                $('#farmDetailsContainer').html('<div class="text-center py-5"><div class="spinner-border text-success"></div></div>');

                $.get('/admin/farms/' + farmId + '/data', function(data) {
                    $('#farmDetailsContainer').html(data.html);

                    // If you use Bootstrap's JS tabs, initialize them
                    var triggerTabList = [].slice.call(document.querySelectorAll('#farmDetailsContainer .nav-link'));
                    triggerTabList.forEach(function (triggerEl) {
                        var tabTrigger = new bootstrap.Tab(triggerEl);
                    });
                }).fail(function() {
                    $('#farmDetailsContainer').html('<div class="alert alert-danger"><i class="feather-alert-triangle flex-shrink-0 me-2"></i>Failed to load farm details.</div>');
                });
            });
        });
    </script>
@endpush
