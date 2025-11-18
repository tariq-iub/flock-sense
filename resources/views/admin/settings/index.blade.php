@extends('layouts.app')

@section('title', 'System Settings')

@push('css')
    <style>
        .stick-to-top {
            position: -webkit-sticky;
            position: sticky;
            top: 5rem;
            left: 0;
            z-index: 2;
            height: 15rem;
            overflow-y: auto;
        }

        .setting-item {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #f0f0f0;
            transition: background-color 0.2s;
        }

        .setting-item:hover {
            background-color: #f8f9fa;
        }

        .setting-item:last-child {
            border-bottom: none;
        }

        .setting-key {
            font-weight: 600;
            color: #495057;
            display: flex;
            align-items: center;
        }

        .setting-value {
            color: #6c757d;
            word-break: break-word;
        }

        .setting-type {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 20px;
            background-color: #CBEFD4;
            color: #3EB780;
            font-weight: 500;
        }

        .btn-icon {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            padding: 0;
            font-size: 0.875rem;
        }

        .add-setting-form {
            background-color: #f8f9fa;
            padding: 1.5rem;
            border-top: 1px solid #e9ecef;
            display: none;
        }

        .add-setting-form.show {
            display: block;
        }

        .json-value {
            background-color: #f1f3f5;
            padding: 0.5rem;
            border-radius: 5px;
            font-family: monospace;
            font-size: 0.875rem;
            max-height: 150px;
            overflow-y: auto;
        }

        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
        }

        .edit-mode {
            background-color: #fff8e1;
        }

        .fade-in {
            animation: fadeIn 0.5s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .group-icon {
            font-size: 1.5rem;
            margin-right: 0.5rem;
        }

        /* Sidebar styles */
        #settings-nav .list-group-item {
            border: 0;
            border-left: 4px solid transparent;
            border-radius: 0;
            padding: 0.75rem 1rem;
            cursor: pointer;
        }

        #settings-nav .list-group-item.active {
            background: #CBEFD4;
            color: #212B36;
            border-left-color: #90EE90;
        }

    </style>
@endpush

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4 class="fw-bold">System Settings</h4>
                    <h6>Manage your application settings.</h6>
                </div>
            </div>
            <ul class="table-top-head">
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header">
                        <i class="ti ti-chevron-up"></i>
                    </a>
                </li>
            </ul>
        </div>
        <div class="container-fluid">
            <div class="row mb-3">
                @if (session('success'))
                <div class="alert alert-{{ session('success') ? 'success' : 'danger' }} d-flex align-items-center justify-content-between" role="alert">
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

        <div class="row">
            <nav class="col-lg-3 mb-4">
                <div class="card settings-card stick-to-top">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-list me-2"></i>Settings Groups</h5>
                    </div>
                    <div class="card-body p-0">
                        <div id="settings-nav" class="list-group list-group-flush"></div>
                    </div>
                </div>
            </nav>
            <div class="col-lg-9">
                <div class="row" id="settings-container">

                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container"></div>

    <!-- Delete Modal -->
    <div class="modal fade" id="delete-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="p-5 px-3 text-center">
                    <span class="rounded-circle d-inline-flex p-2 bg-danger-transparent mb-2">
                        <i class="ti ti-trash fs-24 text-danger"></i>
                    </span>
                        <h4 class="fs-20 fw-bold mb-2 mt-1">Delete Setting</h4>
                        <p class="mb-0 fs-16" id="delete-modal-message">
                            Are you sure you want to delete this settings data?
                        </p>
                        <div class="modal-footer-btn mt-3 d-flex justify-content-center">
                            <button type="button" class="btn btn-secondary fs-13 fw-medium p-2 px-3 me-2" data-bs-dismiss="modal">
                                Cancel
                            </button>
                            <button type="button" class="btn btn-danger fs-13 fw-medium p-2 px-3" id="confirm-delete-btn">
                                Yes Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script>
        const settingsData = @json($settings);
        let modifiedData = JSON.parse(JSON.stringify(settingsData));
        let sectionObserver = null;

        // Group icons and titles
        const groupInfo = {
            company: { icon: 'bi-building', title: 'Company' },
            video: { icon: 'bi-play-circle', title: 'Video' },
            social: { icon: 'bi-share', title: 'Social Media' },
            contact: { icon: 'bi-envelope', title: 'Contacts' }
        };

        // Initialize the page
        document.addEventListener('DOMContentLoaded', function () {
            renderNav();
            renderSettings();
        });

        // Render left navigation
        function renderNav() {
            const nav = document.getElementById('settings-nav');
            if (!nav) return;
            nav.innerHTML = '';

            for (const [group] of Object.entries(modifiedData)) {
                const info = groupInfo[group] || { icon: 'bi-gear', title: `${group.charAt(0).toUpperCase() + group.slice(1)} Settings` };

                const a = document.createElement('a');
                a.href = `#group-${group}`;
                a.id = `nav-item-${group}`;
                a.className = 'list-group-item list-group-item-action';
                a.innerHTML = `<i class="bi ${info.icon} me-2"></i>${info.title}`;
                a.addEventListener('click', (e) => {
                    e.preventDefault();
                    setActiveNav(group);
                    const target = document.getElementById(`group-${group}`);
                    if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                });

                nav.appendChild(a);
            }

            const firstGroup = Object.keys(modifiedData)[0];
            if (firstGroup) setActiveNav(firstGroup);
        }

        function setActiveNav(group) {
            document.querySelectorAll('#settings-nav .list-group-item').forEach(el => el.classList.remove('active'));
            const item = document.getElementById(`nav-item-${group}`);
            if (item) item.classList.add('active');
        }

        function setupSectionObserver() {
            if (sectionObserver) sectionObserver.disconnect();

            const options = { root: null, rootMargin: '0px 0px -60% 0px', threshold: 0.2 };
            sectionObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const id = entry.target.id; // e.g., group-company
                        const group = id.replace('group-', '');
                        setActiveNav(group);
                    }
                });
            }, options);

            Object.keys(modifiedData).forEach(group => {
                const el = document.getElementById(`group-${group}`);
                if (el) sectionObserver.observe(el);
            });
        }

        // Render all settings
        function renderSettings() {
            const container = document.getElementById('settings-container');
            container.innerHTML = '';

            for (const [group, settings] of Object.entries(modifiedData)) {
                const groupCard = createGroupCard(group, settings);
                container.appendChild(groupCard);
            }

            // Refresh observers and nav after render
            setupSectionObserver();
            renderNav();
        }

        // Create a card for a settings group
        function createGroupCard(group, settings) {
            const col = document.createElement('div');
            col.className = 'col-lg-12 fade-in';
            col.id = `group-${group}`;

            const info = groupInfo[group] || { icon: 'bi-gear', title: `${group.charAt(0).toUpperCase() + group.slice(1)} Settings` };

            col.innerHTML = `
                <div class="card settings-card">
                    <div class="card-header card-header-${group}">
                        <div class="d-inline-flex">
                            <span class="title-icon bg-soft-info fs-16 me-2"><i class="ti ${info.icon}"></i></span>
                            <h5 class="card-title mt-2">${info.title}</h5>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="settings-list" id="${group}-settings">
                            ${settings.map(setting => createSettingItem(setting)).join('')}
                        </div>
                        <div class="add-setting-form" id="${group}-add-form">
                            <h6 class="mb-3">Add New Setting</h6>
                            <form id="${group}-form" action="{{ route('web-settings.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="group" value="${group}">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="${group}-key" class="form-label">Key</label>
                                        <input type="text" class="form-control" id="${group}-key" name="key" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="${group}-type" class="form-label">Type</label>
                                        <select class="form-select" id="${group}-type" name="type" required>
                                            <option value="string">String</option>
                                            <option value="url">URL</option>
                                            <option value="json">JSON</option>
                                            <option value="number">Number</option>
                                            <option value="boolean">Boolean</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label for="${group}-value" class="form-label">Value</label>
                                        <textarea class="form-control" id="${group}-value" name="value" rows="2" required></textarea>
                                    </div>
                                    <div class="col-12">
                                        <label for="${group}-description" class="form-label">Description</label>
                                        <input type="text" class="form-control" id="${group}-description" name="description">
                                    </div>
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="${group}-encrypted" name="is_encrypted">
                                            <label class="form-check-label" for="${group}-encrypted">
                                                Encrypted
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-12 d-flex justify-content-end align-items-center">
                                        <button type="submit" class="btn btn-sm btn-success me-2">
                                            <i class="bi bi-plus-circle me-2"></i>Add Setting
                                        </button>
                                        <button type="button" class="btn btn-sm btn-secondary" onclick="toggleAddForm('${group}')">
                                            Cancel
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-footer bg-light d-flex justify-content-between">
                        <button class="btn btn-sm btn-outline-primary" onclick="toggleAddForm('${group}')">
                            <i class="bi bi-plus-circle me-1"></i>Add New
                        </button>
                        <span class="text-muted">${settings.length} settings</span>
                    </div>
                </div>
            `;

            return col;
        }

        // Create a setting item
        function createSettingItem(setting) {
            const valueDisplay = setting.type === 'json'
                ? `<div class="json-value">${JSON.stringify(setting.value, null, 2)}</div>`
                : `<div class="setting-value">${setting.value}</div>`;

            return `
                <div class="setting-item" id="setting-${setting.id}">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <div class="setting-key">
                                <i class="bi bi-tag-fill text-info me-2"></i>
                                ${setting.key}
                            </div>
                        </div>
                        <div class="col-md-6">
                            ${valueDisplay}
                            <div class="mt-1">
                                <small class="text-muted">${setting.description}</small>
                            </div>
                        </div>
                        <div class="col-md-3 text-end">
                            <div class="d-flex align-items-center justify-content-end gap-2">
                                <span class="setting-type">${setting.type}</span>
                                ${setting.is_encrypted ? '<span class="btn btn-sm btn-outline-warning btn-icon"><i class="bi bi-lock-fill" title="Encrypted"></i></span>' : ''}
                                <div class="setting-actions">
                                    <button class="btn btn-sm btn-outline-info btn-icon" onclick="editSetting(${setting.id})" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger btn-icon" onclick="deleteSetting(${setting.id})" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    <form action="{{ url('admin/system/web-settings') }}/${setting.id}" method="POST" id="delete${setting.id}">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        // Toggle add form visibility
        function toggleAddForm(group) {
            const form = document.getElementById(`${group}-add-form`);
            form.classList.toggle('show');
        }

        // Edit a setting
        function editSetting(id) {
            // Find the setting
            let setting = null;
            let group = null;

            for (const [g, settings] of Object.entries(modifiedData)) {
                const found = settings.find(s => s.id === id);
                if (found) {
                    setting = found;
                    group = g;
                    break;
                }
            }

            if (!setting) return;

            // Get the setting element
            const settingElement = document.getElementById(`setting-${id}`);

            // Create edit form
            let valueInput = '';
            if (setting.type === 'json') {
                valueInput = `<textarea class="form-control" id="edit-value-${id}" rows="3">${JSON.stringify(setting.value, null, 2)}</textarea>`;
            } else if (setting.type === 'boolean') {
                valueInput = `
                    <select class="form-select" id="edit-value-${id}">
                        <option value="true" ${setting.value === true ? 'selected' : ''}>True</option>
                        <option value="false" ${setting.value === false ? 'selected' : ''}>False</option>
                    </select>
                `;
            } else {
                valueInput = `<input type="text" class="form-control" id="edit-value-${id}" value="${setting.value}">`;
            }

            settingElement.classList.add('edit-mode');
            settingElement.innerHTML = `
                <div class="row align-items-center">
                    @csrf
                    <div class="col-md-3">
                        <div class="setting-key">
                            <i class="bi bi-tag-fill me-2 text-primary"></i>
                            ${setting.key}
                        </div>
                    </div>
                    <div class="col-md-6">
                        ${valueInput}
                        <div class="mt-1">
                            <input type="text" class="form-control form-control-sm" id="edit-description-${id}" value="${setting.description}">
                        </div>
                    </div>
                    <div class="col-md-3 text-end">
                        <div class="setting-actions">
                            <button class="btn btn-sm btn-success btn-icon" onclick="saveSetting(${id}, '${group}')" title="Save">
                                <i class="bi bi-check-lg"></i>
                            </button>
                            <button class="btn btn-sm btn-secondary btn-icon" onclick="cancelEdit(${id}, '${group}')" title="Cancel">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
                `;
        }

        // Save edited setting
        function saveSetting(id, group) {
            const setting = modifiedData[group].find(s => s.id === id);
            if (!setting) return;

            const valueElement = document.getElementById(`edit-value-${id}`);
            const descriptionElement = document.getElementById(`edit-description-${id}`);

            // Parse value based on type
            let parsedValue = valueElement.value;
            if (setting.type === 'json') {
                try {
                    parsedValue = JSON.parse(valueElement.value);
                } catch (e) {
                    showToast('Invalid JSON format', 'danger');
                    return;
                }
            } else if (setting.type === 'boolean') {
                parsedValue = valueElement.value === 'true';
            } else if (setting.type === 'number') {
                parsedValue = parseFloat(valueElement.value);
                if (isNaN(parsedValue)) {
                    showToast('Invalid number format', 'danger');
                    return;
                }
            }

            // Update local copy
            setting.value = parsedValue;
            setting.description = descriptionElement.value;
            setting.updated_at = new Date().toISOString();

            try {
                $.ajax({
                    url: `/admin/system/web-settings/${id}`,
                    method: 'PUT',
                    data: JSON.stringify({
                        value: setting.value,
                        type: setting.type,
                        description: setting.description,
                    }),
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    },
                    success(response) {
                        renderSettings();
                        showToast(`Setting "${setting.key}" is updated successfully.`, 'success');
                    },
                    error(xhr) {
                        let errorMessage = 'Update failed';

                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMessage = Object.values(xhr.responseJSON.errors).flat().join(', ');
                        }

                        showToast(`Error updating settings: ${errorMessage}`, 'danger');
                    }
                });
            } catch (error) {
                showToast(`Error updating settings: ${error}`, 'danger');
            }
        }

        // Cancel edit
        function cancelEdit(id, group) {
            renderSettings();
        }

        let deleteId = null;
        // Delete a setting
        function deleteSetting(id) {
            deleteId = id;
            var modal = new bootstrap.Modal(document.getElementById('delete-modal'));
            modal.show();
        }

        document.getElementById('confirm-delete-btn').addEventListener('click', function() {
            if (deleteId) {
                document.getElementById('delete' + deleteId).submit();
            }
        });

        // function deleteSetting(id) {
        //     deleteId = id;
        //
        //     // Find and remove the setting
        //     for (const [group, settings] of Object.entries(modifiedData)) {
        //         const index = settings.findIndex(s => s.id === id);
        //         if (index !== -1) {
        //             const setting = settings[index];
        //             settings.splice(index, 1);
        //             renderSettings();
        //             showToast(`Setting "${setting.key}" deleted successfully`, 'success');
        //             return;
        //         }
        //     }
        // }

        // Show toast notification
        function showToast(message, type = 'info') {
            const toastContainer = document.querySelector('.toast-container');
            const toastId = 'toast-' + Date.now();

            const toastHtml = `
                <div id="${toastId}" class="toast align-items-center text-white bg-${type === 'danger' ? 'danger' : type === 'success' ? 'success' : 'primary'} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            `;

            toastContainer.insertAdjacentHTML('beforeend', toastHtml);

            const toastElement = document.getElementById(toastId);
            const toast = new bootstrap.Toast(toastElement, { autohide: true, delay: 3000 });
            toast.show();

            toastElement.addEventListener('hidden.bs.toast', () => {
                toastElement.remove();
            });
        }

    </script>
@endpush
