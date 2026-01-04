<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .table-nowrap td, .table-nowrap th {
        white-space: nowrap;
    }

    .avatar {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2.5rem;
        height: 2.5rem;
    }

    .avatar-sm {
        width: 2rem;
        height: 2rem;
    }

    .avatar-xl {
        width: 5rem;
        height: 5rem;
    }

    .avatar-md {
        width: 3rem;
        height: 3rem;
    }

    .btn-group .btn {
        border-radius: 0.375rem;
    }

    .btn-group .btn:first-child {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }

    .btn-group .btn:last-child {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }
</style>


<div class="card shadow-sm">
    <!-- Card Header with gradient background -->
    <div class="card-header bg-gradient-primary text-white">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="mb-1">{{ $shed->name }}</h3>
                <p class="text-muted mb-0">
                    <i class="ti ti-map-pin me-1"></i>{{ $shed->farm?->address }} | {{ $shed->farm?->city?->name }}
                </p>
            </div>
            <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addFlockModal">
                <i class="ti ti-plus me-2"></i>Add Flock
            </button>
        </div>
    </div>

    <div class="card-body">
        <!-- Shed Information Section -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="d-flex align-items-center p-3 bg-light rounded-3 mb-3">
                    <div class="flex-shrink-0">
                        <div class="avatar avatar-md bg-secondary text-white rounded-circle">
                            <i class="ti ti-building-factory-2"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0">Shed Details</h6>
                        <p class="mb-0 text-muted">
                            Capacity: <span class="fw-semibold">{{ number_format($shed->capacity, 0) }}</span> |
                            Type: <span class="fw-semibold">{{ ucwords($shed->type) }}</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                @if($shed->latestFlock?->count() > 0)
                    <div class="d-flex align-items-center p-3 bg-light rounded-3 mb-3">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-md bg-soft-success text-white rounded-circle">
                                <img src="{{ asset('assets/img/icons/hen-icon.svg') }}" alt="F">
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">{{ $shed->latestFlock?->name }}</h6>
                            <p class="mb-0 text-muted">
                                Started: <span class="fw-semibold">{{ $shed->latestFlock?->start_date->format('d M Y') }}</span> |
                                Age: <span class="fw-semibold">{{ $shed->latestFlock?->age }} Days</span>
                            </p>
                        </div>
                    </div>
                @else
                    <div class="d-flex align-items-center p-3 bg-light rounded-3 mb-3">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-md bg-danger text-white rounded-circle">
                                <i class="ti ti-alert-circle"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">No Active Flock</h6>
                            <p class="mb-0 text-muted">Click "Add Flock" to start a new cycle</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Flocks History Section -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Flocks History</h5>
            <span class="badge bg-info">{{ count($shed->latestFlocks) }} Total</span>
        </div>

        @if(count($shed->latestFlocks) > 0)
            <div class="table-responsive">
                <table class="table table-hover table-nowrap">
                    <thead>
                    <tr>
                        <th>Flock</th>
                        <th>Breed</th>
                        <th class="text-center">Start Date</th>
                        <th class="text-center">Age</th>
                        <th class="text-center">Start Count</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($shed->latestFlocks as $flock)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm bg-soft-info text-white rounded-circle me-2">
                                        <img src="{{ asset('assets/img/icons/hen-icon.svg') }}" alt="F">
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $flock->name }}</div>
                                        <div class="text-muted small">ID: #{{ $flock->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $flock->breed->name }}</td>
                            <td class="text-center">{{ $flock->start_date->format('d M Y') }}</td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark">{{ $flock->age }} Days</span>
                            </td>
                            <td class="text-center">{{ number_format($flock->chicken_count) }}</td>
                            <td class="text-center">
                                @if($flock->end_date != null)
                                    <span class="badge bg-success">Completed</span>
                                @else
                                    <span class="badge bg-danger">Running</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm edit-flock"
                                            data-flock-id="{{ $flock->id }}"
                                            data-flock-name="{{ $flock->name }}"
                                            title="Edit Flock">
                                        <i class="ti ti-edit"></i>
                                    </button>

                                    <button type="button" class="btn btn-sm text-danger open-delete-modal"
                                            data-flock-id="{{ $flock->id }}"
                                            data-flock-name="{{ $flock->name }}"
                                            title="Delete Flock">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </div>
                                <form action="{{ route('admin.flocks.destroy', $flock->id) }}" method="POST" id="delete{{ $flock->id }}" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <div class="avatar avatar-xl bg-light text-primary rounded-circle mx-auto mb-3">
                    <i class="ti ti-bird-off fs-1"></i>
                </div>
                <h5 class="mb-2">No Flocks Available</h5>
                <p class="text-muted mb-3">There are no flocks recorded for this shed yet.</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFlockModal">
                    <i class="ti ti-plus me-2"></i>Add First Flock
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Add Flock Modal -->
<div class="modal fade" id="addFlockModal" tabindex="-1" aria-labelledby="addFlockModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.flocks.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addFlockModalLabel">
                        Add New Flock
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="alert alert-info d-flex align-items-center" role="alert">
                        <i class="ti ti-info-circle me-2"></i>
                        <div>
                            Adding a new flock to shed <strong>{{ $shed->name }}</strong>.
                        </div>
                    </div>

                    <div class="row g-3">
                        <!-- Flock Name -->
                        <div class="col-md-6">
                            <label for="name" class="form-label">
                                <i class="ti ti-tag me-1"></i>Flock Name
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <div class="invalid-feedback">Please enter a flock name for identification.</div>
                        </div>

                        <!-- Breed -->
                        <div class="col-md-6">
                            <label for="breed_id" class="form-label">
                                <i class="ti ti-dna me-1"></i>Breed
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="breed_id" name="breed_id" required>
                                <option value="">Select Breed</option>
                                @foreach($breeds as $breed)
                                    <option value="{{ $breed->id }}">{{ $breed->name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">Please select a breed.</div>
                        </div>

                        <!-- Chicken Count -->
                        <div class="col-md-6">
                            <label for="chicken_count" class="form-label">
                                <i class="ti ti-hash me-1"></i>Chicken Count
                                <span class="text-danger">*</span>
                            </label>
                            <input type="number" id="chicken_count" name="chicken_count" class="form-control" required>
                            <div class="invalid-feedback">Please enter the initial chicken count.</div>
                        </div>

                        <!-- Start Date -->
                        <div class="col-md-6">
                            <label for="start_date" class="form-label">
                                <i class="ti ti-calendar me-1"></i>Start Date
                                <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control" id="start_date" name="start_date" required/>
                            <div class="invalid-feedback">Please select a start date.</div>
                        </div>

                        <!-- End Date -->
                        <div class="col-md-6">
                            <label for="end_date" class="form-label">
                                <i class="ti ti-calendar-off me-1"></i>End Date
                            </label>
                            <input type="date" class="form-control" id="end_date" name="end_date"/>
                            <div class="form-text">Leave empty if the flock is still active</div>
                        </div>

                        <!-- Shed ID (Hidden) -->
                        <input type="hidden" name="shed_id" value="{{ $shed->id }}">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success me-2">
                        <i class="ti ti-device-floppy me-2"></i>Save Flock
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ti ti-x me-2"></i>Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
