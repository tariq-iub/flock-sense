@extends('layouts.app')

@section('title', 'Create Newsletter')

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4 class="fw-bold">Create Newsletter</h4>
                    <h6>Create a new email newsletter to send to subscribers.</h6>
                </div>
            </div>
            <ul class="table-top-head">
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i class="ti ti-chevron-up"></i></a>
                </li>
            </ul>
        </div>

        <div class="container-fluid">
            <div class="row mb-3">
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

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="ti ti-mail text-primary me-2"></i>Newsletter Details
                        </h5>
                    </div>
                    <form action="{{ route('newsletters.store') }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label">Subject<span class="text-danger">*</span></label>
                                    <input type="text"
                                           name="subject"
                                           class="form-control @error('subject') is-invalid @enderror"
                                           value="{{ old('subject') }}"
                                           placeholder="Enter newsletter subject"
                                           required>
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">Preview Text</label>
                                    <input type="text"
                                           name="preview_text"
                                           class="form-control @error('preview_text') is-invalid @enderror"
                                           value="{{ old('preview_text') }}"
                                           placeholder="Brief preview shown in inbox (max 500 characters)"
                                           maxlength="500">
                                    @error('preview_text')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">This text appears in email clients' preview pane.</small>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">HTML Content<span class="text-danger">*</span></label>
                                    <textarea name="content_html"
                                              id="contentHtml"
                                              class="form-control @error('content_html') is-invalid @enderror"
                                              rows="12"
                                              placeholder="Enter HTML content for the newsletter"
                                              required>{{ old('content_html') }}</textarea>
                                    @error('content_html')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">Plain Text Content</label>
                                    <textarea name="content_text"
                                              class="form-control @error('content_text') is-invalid @enderror"
                                              rows="6"
                                              placeholder="Enter plain text version (optional)">{{ old('content_text') }}</textarea>
                                    @error('content_text')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Recommended for clients that don't support HTML.</small>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Status<span class="text-danger">*</span></label>
                                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                        <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="sending" {{ old('status') === 'sending' ? 'selected' : '' }}>Sending</option>
                                        <option value="sent" {{ old('status') === 'sent' ? 'selected' : '' }}>Sent</option>
                                        <option value="failed" {{ old('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Schedule Send Date & Time</label>
                                    <input type="datetime-local"
                                           name="send_at"
                                           class="form-control @error('send_at') is-invalid @enderror"
                                           value="{{ old('send_at') }}">
                                    @error('send_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Leave empty to send immediately or save as draft.</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-end gap-2">
                            <a href="{{ route('newsletters.index') }}" class="btn btn-secondary">
                                <i class="ti ti-x me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="ti ti-device-floppy me-1"></i>Save Newsletter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
