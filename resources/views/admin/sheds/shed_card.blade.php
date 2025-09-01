<div class="card">
    <div class="card-header">
        <div class="card-title d-flex justify-content-between align-items-center">
            {{ $shed->name }}
            <a href="javascript:void(0);" class="btn btn-sm btn-outline-light" data-bs-toggle="modal" data-bs-target="#addFlockModal">
                Add Flock
            </a>
        </div>
    </div>

    <div class="card-body">
        <div class="d-flex justify-content-between mb-3">
            <div class="text-info">
                <i class="ti ti-map-pin me-2"></i>{{ $shed->farm?->address }} | {{ $shed->farm?->city?->name }}<br>
                <i class="ti ti-calculator me-2"></i>Capacity: {{ number_format($shed->capacity, 0) }}<br>
                <i class="ti ti-brand-abstract me-2"></i>Shed Type: {{ ucwords($shed->type) }}
            </div>
            <div class="text-info">
                @if($shed->latestFlock?->count() > 0)
                    <strong>{{ $shed->latestFlock?->name }}</strong><br>
                    Start Date: {{ $shed->latestFlock?->start_date->format('d-m-Y') }}<br>
                    Flock Age: {{ $shed->latestFlock?->age }} Days
                @else
                    <span class="badge bg-soft-danger">No Flock Cycled</span>
                @endif
            </div>
        </div>
        <h5 class="mb-3">Flocks History</h5>
        @if(count($shed->latestFlocks) > 0)
            <div class="table-responsive">
                <table class="table table-borderless custom-table">
                    <thead class="thead-light">
                    <tr>
                        <th>Flock</th>
                        <th>Breed</th>
                        <th class="text-center">Start Date</th>
                        <th class="text-center">Age</th>
                        <th class="text-center">Start Count</th>
                        <th class="no-sort"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($shed->latestFlocks as $flock)
                        <tr>
                            <td class="fs-12">{{ $flock->name }}</td>
                            <td class="fs-12">{{ $flock->breed->name }}</td>
                            <td class="text-center fs-12">{{ $flock->start_date->format('d-m-Y') }}</td>
                            <td class="text-center fs-12">
                                {{ $flock->age }} Days
                            </td>
                            <td class="text-center fs-12">{{ $flock->chicken_count }}</td>
                            <td class="action-table-data">
                                <div class="action-icon d-inline-flex float-end">
                                    <a href="javascript:void(0);"
                                       class="p-2 border rounded me-2 edit-flock"
                                       title="Edit Flock"
                                       data-flock-id="{{ $flock->id }}"
                                       data-flock-name="{{ $flock->name }}">
                                        <i class="ti ti-edit"></i>
                                    </a>

                                    <a href="javascript:void(0);"
                                       title="Delete Flock"
                                       data-flock-id="{{ $flock->id }}"
                                       data-flock-name="{{ $flock->name }}"
                                       class="p-2 open-delete-modal" onclick="OpenModal(this)">
                                        <i data-feather="trash-2" class="feather-trash-2"></i>
                                    </a>
                                    <form action="{{ route('admin.flocks.destroy', $flock->id) }}" method="POST" id="delete{{ $flock->id }}">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-danger">No flocks available for this shed.</div>
        @endif
    </div>
</div>

<!-- Add Flock Modal -->
<div class="modal fade" id="addFlockModal" tabindex="-1" aria-labelledby="addFlockModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.flocks.store') }}" method="POST"
                  class="needs-validation" novalidate>
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addFlockModalLabel">Add Flock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        {{-- Flock Name --}}
                        <div class="col-lg-6 mb-3">
                            <label for="name" class="form-label">Flock Name<span class="text-danger ms-1">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <div class="invalid-feedback">Flock name should be entered for identification.</div>
                        </div>

                        {{-- Shed --}}
                        <div class="col-lg-6 mb-3">
                            <label for="shed_id" class="form-label">Shed<span class="text-danger ms-1">*</span></label>
                            <select class="form-select basic-select" id="shed_id" name="shed_id" required>
                                <option value="{{ $shed->id }}">{{ $shed->name }}</option>
                            </select>
                            <div class="invalid-feedback">Select a shed first as flock is cycled in a shed.</div>
                        </div>

                        {{-- Breed --}}
                        <div class="col-lg-6 mb-3">
                            <label for="breed_id" class="form-label">Breed<span class="text-danger ms-1">*</span></label>
                            <select class="form-select basic-select" id="breed_id" name="breed_id" required>
                                <option value="">Select Breed</option>
                                @foreach($breeds as $breed)
                                    <option value="{{ $breed->id }}">{{ $breed->name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">Flock breed is required to be selected.</div>
                        </div>

                        {{-- Chicken Count --}}
                        <div class="col-lg-6 mb-3">
                            <label for="chicken_count" class="form-label">Chicken Count<span class="text-danger ms-1">*</span></label>
                            <input type="number" id="chicken_count" name="chicken_count" class="form-control" required>
                            <div class="invalid-feedback">Please mention the flock initial chicken count.</div>
                        </div>

                        {{-- Start Date --}}
                        <div class="col-lg-6 mb-3">
                            <label for="start_date" class="form-label">Start Date<span class="text-danger ms-1">*</span></label>
                            <input type="date" class="form-control"
                                   id="start_date" name="start_date" required/>
                            <div class="invalid-feedback">Flock cycle start date is required.</div>
                        </div>

                        {{-- End Date --}}
                        <div class="col-lg-6 mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control"
                                   id="end_date" name="end_date"/>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success me-2">Save Flock</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

