@extends('layouts.app')

@section('title', 'User Profile')

@section('content')
    <div class="content pb-0">
        <div class="row">
            <div class="container-fluid">
                <div class="row mb-3">
                    @if (session('success'))
                        <div class="alert alert-success d-flex align-items-center justify-content-between" role="alert">
                            <div>
                                <i class="feather-check-circle flex-shrink-0 me-2"></i>
                                {{ session('success') }}
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                <i class="fas fa-xmark"></i>
                            </button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
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
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="bg-light rounded p-3 mb-4">
                            <div class="text-center mb-3">
                                <a href="javascript:void(0);" class="avatar avatar-xl online avatar-rounded">
                                    @php
                                        $media = $user->media()->orderBy('order_column')->first();
                                        $path = $media ? $media->url : asset("assets/img/user.jpg");
                                    @endphp
                                    <img src="{{ $path }}" alt="Img">
                                </a>
                                <h5 class="mb-1"><a href="javascript:void(0);">{{ $user->name }} </a></h5>
                                <p class="fs-12">
                                    {{ $user->email }}<br>
                                    {{ $user->phone }}
                                </p>
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

                        @if(auth()->user()->hasAnyRole(['admin', 'owner']))
                        <div class="mb-4">
                            <a href="javascript:void(0);"
                               class="btn btn-primary d-inline-flex align-items-center justify-content-center w-100"
                               data-bs-toggle="modal" data-bs-target="#addFarmModal">
                                <i class="ti ti-circle-plus me-2"></i>Add Farm
                            </a>
                        </div>
                        @endif

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

                        <hr>
                        <div>
                            <div class="mb-2">
                                <h5>Change Password</h5>
                            </div>
                            <div>
                                <form action="{{ route('user.update-password', $user) }}"
                                      method="POST" class="needs-validation" novalidate>
                                    @csrf
                                    @method('PUT')

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

                                    <button type="submit" class="btn btn-warning w-100">Update Password</button>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-8" id="farmDetailsContainer">
            </div>
        </div>
    </div>

    <!-- Add Farm Modal -->
    <div class="modal fade" id="addFarmModal" tabindex="-1" aria-labelledby="addFarmModalLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('admin.farms.store') }}" class="needs-validation" novalidate method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addFarmModalLabel">Add Farm</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            {{-- Farm Name --}}
                            <div class="col-lg-6 mb-3">
                                <label for="name" class="form-label">Farm Name<span class="text-danger ms-1">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" required>
                                <div class="invalid-feedback">Farm name is required.</div>
                            </div>

                            {{-- Owner --}}
                            <div class="col-lg-6 mb-3">
                                <label for="owner_id" class="form-label">Farm Owner<span class="text-danger ms-1">*</span></label>
                                <select class="form-select basic-select" id="owner_id" name="owner_id" required>
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                </select>
                                <div class="invalid-feedback">Farm owner is required.</div>
                            </div>

                            {{-- Province --}}
                            <div class="col-lg-4 mb-3">
                                <label for="province" class="form-label">Province<span class="text-danger ms-1">*</span></label>
                                <select class="form-select basic-select" id="province" name="province_id" required>
                                    <option value="" selected disabled>Select Province</option>
                                    @foreach($provinces as $province)
                                        <option value="{{ $province->id }}">{{ $province->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Province is required.</div>
                            </div>

                            {{-- District --}}
                            <div class="col-lg-4 mb-3">
                                <label for="district" class="form-label">District<span class="text-danger ms-1">*</span></label>
                                <select class="form-select basic-select" id="district" name="district_id" required>
                                    <option value="" selected disabled>Select District</option>
                                </select>
                                <div class="invalid-feedback">District is required.</div>
                            </div>

                            {{-- City --}}
                            <div class="col-lg-4 mb-3">
                                <label for="city" class="form-label">City<span class="text-danger ms-1">*</span></label>
                                <select class="form-select select2" id="city" name="city_id" required>
                                    <option value="" selected disabled>Select City</option>
                                </select>
                                <div class="invalid-feedback">City is required.</div>
                            </div>

                            {{-- Address --}}
                            <div class="col-lg-12 mb-3">
                                <label for="address" class="form-label">Address<span class="text-danger ms-1">*</span></label>
                                <textarea class="form-control" id="address" name="address" rows="2" required></textarea>
                                <div class="invalid-feedback">Address is required.</div>
                            </div>

                            {{-- Latitude --}}
                            <div class="col-lg-6 mb-3">
                                <label for="latitude" class="form-label">Latitude</label>
                                <input type="text" class="form-control" id="latitude" name="latitude">
                            </div>

                            {{-- Longitude --}}
                            <div class="col-lg-6 mb-3">
                                <label for="longitude" class="form-label">Longitude</label>
                                <input type="text" class="form-control" id="longitude" name="longitude">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success me-2">Save Farm</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
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

    <script>
        function resetSelect(selectElement, placeholder) {
            // Clear existing options
            $(selectElement).empty();

            // Create a new Option element
            const placeholderOption = new Option(placeholder, '');

            // Set the disabled and selected attributes on the placeholder option
            // so it cannot be chosen and serves as a default.
            $(placeholderOption).prop('disabled', true).prop('selected', true);

            // Append the placeholder option to the select element
            $(selectElement).append(placeholderOption);

            // Trigger a change to update the UI
            $(selectElement).val('').trigger('change');
        }

        function populateSelect(selectElement, data, placeholder, selectedId = null) {
            // Reset the select with the disabled placeholder
            resetSelect(selectElement, placeholder);

            // Check if there is data to populate
            if (data && data.length > 0) {
                data.forEach(item => {
                    const option = new Option(item.name, item.id, false, item.id == selectedId);
                    $(selectElement).append(option);
                });
            }

            // Trigger change after populating
            $(selectElement).trigger('change');

            // If a specific option was selected, remove the disabled attribute from the placeholder
            // This isn't strictly necessary but can be a good practice for some UIs
            if (selectedId !== null && selectedId !== '') {
                $(selectElement).find('option:disabled').prop('selected', false);
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const provinceSelect = document.getElementById('province');
            const districtSelect = document.getElementById('district');
            const citySelect = document.getElementById('city');

            $(provinceSelect).on('change', function () {
                const provinceId = this.value;
                resetSelect(districtSelect, 'Select District');
                resetSelect(citySelect, 'Select City');

                if (provinceId) {
                    fetch(`/api/v1/districts/${provinceId}`)
                        .then(response => response.json())
                        .then(data => {
                            populateSelect(districtSelect, data, 'Select District');
                        });
                }
            });

            $(districtSelect).on('change', function () {
                const districtId = this.value;
                resetSelect(citySelect, 'Select City');

                if (districtId) {
                    fetch(`/api/v1/cities/${districtId}`)
                        .then(response => response.json())
                        .then(data => {
                            populateSelect(citySelect, data, 'Select City');
                        });
                }
            });
        });
    </script>
@endpush
