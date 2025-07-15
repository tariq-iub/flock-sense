@extends('layouts.app')

@section('title', 'Create User')

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4 class="fw-bold">User Profile</h4>
                    <h6>View user's credentials and attached forms.</h6>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4>{{ $user->name }}</h4>
            </div>
            <div class="card-body profile-body">
                <h5 class="mb-2"><i class="ti ti-user text-primary me-1"></i>Basic Information</h5>
                <div class="profile-pic-upload image-field">
                    <div class="profile-pic p-2">
                        @php
                            $firstMedia = $user->media()->orderBy('order_column')->first();
                            $path = asset("assets/img/user.jpg");
                            if ($firstMedia && \File::exists(public_path($firstMedia->file_path))) {
                                $path = asset($firstMedia->file_path);
                            }
                        @endphp
                        <img src="{{ $path  }}" class="object-fit-cover h-100 rounded-1" alt="user">
                    </div>
                    <div class="mb-3">
                        <div class="image-upload mb-0 d-inline-flex">
                            <input type="file">
                            <div class="btn btn-primary fs-13">Change Image</div>
                        </div>
                        <p class="mt-2">Upload an image below 2 MB, Accepted File format JPG, PNG</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-sm-12">
                        <div class="mb-3">
                            <label class="form-label">User Name<span class="text-danger ms-1">*</span></label>
                            <input type="text" class="form-control" value="{{ $user->name }}" required>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12">
                        <div class="mb-3">
                            <label class="form-label">Last Name<span class="text-danger ms-1">*</span></label>
                            <input type="text" class="form-control" value="Jordan">
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12">
                        <div class="mb-3">
                            <label>Email<span class="text-danger ms-1">*</span></label>
                            <input type="email" class="form-control" value="jeffry@example.com">
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12">
                        <div class="mb-3">
                            <label class="form-label">Phone Number<span class="text-danger ms-1">*</span></label>
                            <input type="text" value="+17468314286" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12">
                        <div class="mb-3">
                            <label class="form-label">User Name<span class="text-danger ms-1">*</span></label>
                            <input type="text" class="form-control" value="Jeffry Jordan">
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12">
                        <div class="mb-3">
                            <label class="form-label">Password<span class="text-danger ms-1">*</span></label>
                            <div class="pass-group">
                                <input type="password" class="pass-input form-control" value="********">
                                <i class="ti ti-eye-off toggle-password"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 d-flex justify-content-end">
                        <a href="javascript:void(0);" class="btn btn-secondary me-2 shadow-none">Cancel</a>
                        <a href="javascript:void(0);" class="btn btn-primary shadow-none">Save Changes</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
