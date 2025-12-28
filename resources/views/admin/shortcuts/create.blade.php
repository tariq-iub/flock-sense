@extends('layouts.app')

@section('title', isset($shortcut) ? 'Edit Shortcut' : 'Create Shortcut')

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4 class="fw-bold">{{ isset($shortcut) ? 'Edit Shortcut' : 'Create Shortcut' }}</h4>
                    <h6>{{ isset($shortcut) ? 'Update shortcut details' : 'Add a new quick access shortcut' }}</h6>
                </div>
            </div>
            <div class="page-btn">
                <a href="{{ route('shortcuts.index') }}" class="btn btn-secondary">
                    <i class="ti ti-arrow-left me-1"></i>Back to List
                </a>
            </div>
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
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Shortcut Information</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ isset($shortcut) ? route('shortcuts.update', $shortcut) : route('shortcuts.store') }}"
                              method="POST"
                              id="shortcutForm">
                            @csrf
                            @if(isset($shortcut))
                                @method('PUT')
                            @endif

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label" for="title">Title <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('title') is-invalid @enderror"
                                           id="title"
                                           name="title"
                                           placeholder="e.g., Dashboard, Settings, Reports"
                                           value="{{ old('title', $shortcut->title ?? '') }}"
                                           required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">The display name for this shortcut</small>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label" for="url">URL <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('url') is-invalid @enderror"
                                           id="url"
                                           name="url"
                                           placeholder="e.g., /dashboard, /admin/settings"
                                           value="{{ old('url', $shortcut->url ?? '') }}"
                                           required>
                                    @error('url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">The path or URL this shortcut will link to</small>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label" for="icon">Icon (Tabler Icons)</label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="icon-preview">
                                            <i class="{{ old('icon', $shortcut->icon ?? 'ti ti-link') }} fs-20"></i>
                                        </span>
                                        <input type="text"
                                               class="form-control @error('icon') is-invalid @enderror"
                                               id="icon"
                                               name="icon"
                                               placeholder="e.g., ti ti-dashboard, ti ti-settings"
                                               value="{{ old('icon', $shortcut->icon ?? '') }}">
                                        @error('icon')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="text-muted">
                                        Browse icons at <a href="https://tabler.io/icons" target="_blank">tabler.io/icons</a>
                                    </small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="group">Group <span class="text-danger">*</span></label>
                                    <select class="form-select @error('group') is-invalid @enderror"
                                            id="group"
                                            name="group"
                                            required>
                                        <option value="">Select Group</option>
                                        <option value="admin" {{ old('group', $shortcut->group ?? '') === 'admin' ? 'selected' : '' }}>
                                            Admin
                                        </option>
                                        <option value="user" {{ old('group', $shortcut->group ?? '') === 'user' ? 'selected' : '' }}>
                                            User
                                        </option>
                                    </select>
                                    @error('group')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Who can see this shortcut</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="default">Default Shortcut</label>
                                    <div class="mt-2">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input"
                                                   type="checkbox"
                                                   id="default"
                                                   name="default"
                                                   value="1"
                                                   {{ old('default', $shortcut->default ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="default">
                                                Show by default for all users
                                            </label>
                                        </div>
                                    </div>
                                    <small class="text-muted">Default shortcuts appear automatically</small>
                                </div>

                                <div class="col-md-12">
                                    <div class="d-flex justify-content-end gap-2">
                                        <button type="submit" class="btn btn-success">
                                            <i class="ti ti-device-floppy me-1"></i>
                                            {{ isset($shortcut) ? 'Update Shortcut' : 'Create Shortcut' }}
                                        </button>

                                        <a href="{{ route('shortcuts.index') }}" class="btn btn-secondary">
                                            <i class="ti ti-x me-1"></i>Cancel
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Preview</h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="shortcut-preview p-4 border rounded bg-light">
                            <div class="mb-3">
                                <span class="badge bg-soft-primary text-dark border p-3" id="preview-icon-container">
                                    <i class="{{ old('icon', $shortcut->icon ?? 'ti ti-link') }} fs-40" id="preview-icon"></i>
                                </span>
                            </div>
                            <h6 class="mb-1" id="preview-title">{{ old('title', $shortcut->title ?? 'Shortcut Title') }}</h6>
                            <small class="text-muted" id="preview-url">{{ old('url', $shortcut->url ?? '/url') }}</small>
                            <div class="mt-3">
                                <span class="badge bg-soft-secondary text-dark border" id="preview-group">
                                    {{ ucfirst(old('group', $shortcut->group ?? 'user')) }}
                                </span>
                                @if(old('default', $shortcut->default ?? false))
                                    <span class="badge bg-soft-success text-dark border">Default</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Popular Icons</h5>
                    </div>
                    @php
                        $tiIcons = [
                            'Dashboard'      => 'ti ti-dashboard',
                            'Settings'       => 'ti ti-settings',
                            'Users'          => 'ti ti-users',
                            'Analytics'      => 'ti ti-chart-bar',
                            'Devices'        => 'ti ti-device-desktop',
                            'QR Code'        => 'ti ti-qrcode',
                            'Building'       => 'ti ti-building',
                            'Package'        => 'ti ti-package',
                            'File'           => 'ti ti-file',
                            'Report'         => 'ti ti-report',
                            'Notifications'  => 'ti ti-bell',
                            'Mail'           => 'ti ti-mail',

                            // From the grid links (using <p> text as "title")
                            'Product'        => 'ti ti-square-plus',
                            'Purchase'       => 'ti ti-shopping-bag',
                            'Sale'           => 'ti ti-shopping-cart',
                            'Expense'        => 'ti ti-file-text',
                            'Quotation'      => 'ti ti-device-floppy',
                            'Return'         => 'ti ti-copy',
                            'User'           => 'ti ti-user',
                            'Customer'       => 'ti ti-users',
                            'Biller'         => 'ti ti-shield',
                            'Supplier'       => 'ti ti-user-check',
                            'Transfer'       => 'ti ti-truck',

                            // Standalone icon mentioned
                            'Brand Codepen'  => 'ti ti-brand-codepen',
                        ];
                    @endphp
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($tiIcons as $title => $icon)
                                <button type="button"
                                        class="btn btn-outline-secondary btn-sm icon-btn"
                                        data-icon="{{ $icon }}"
                                        title="{{ $title }}">
                                    <i class="{{ $icon }}"></i>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const titleInput = document.getElementById('title');
            const urlInput = document.getElementById('url');
            const iconInput = document.getElementById('icon');
            const groupSelect = document.getElementById('group');
            const defaultCheckbox = document.getElementById('default');

            const previewTitle = document.getElementById('preview-title');
            const previewUrl = document.getElementById('preview-url');
            const previewIcon = document.getElementById('preview-icon');
            const previewIconPreview = document.querySelector('#icon-preview i');
            const previewGroup = document.getElementById('preview-group');

            // Update preview on input
            titleInput.addEventListener('input', function() {
                previewTitle.textContent = this.value || 'Shortcut Title';
            });

            urlInput.addEventListener('input', function() {
                previewUrl.textContent = this.value || '/url';
            });

            iconInput.addEventListener('input', function() {
                const iconClass = this.value || 'ti ti-link';
                previewIcon.className = iconClass + ' fs-40';
                previewIconPreview.className = iconClass + ' fs-20';
            });

            groupSelect.addEventListener('change', function() {
                previewGroup.textContent = this.value ? this.value.charAt(0).toUpperCase() + this.value.slice(1) : 'User';
            });

            // Quick icon selection
            document.querySelectorAll('.icon-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const icon = this.getAttribute('data-icon');
                    iconInput.value = icon;
                    previewIcon.className = icon + ' fs-40';
                    previewIconPreview.className = icon + ' fs-20';
                });
            });
        });
    </script>
@endpush
