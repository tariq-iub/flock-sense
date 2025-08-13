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
                <i class="ti ti-map-pin me-2"></i>{{ $shed->farm->address }} | {{ $shed->farm->city->name }}<br>
                <i class="ti ti-calculator me-2"></i>Capacity: {{ number_format($shed->capacity, 0) }}<br>
                <i class="ti ti-brand-abstract me-2"></i>Shed Type: {{ ucwords($shed->type) }}
            </div>
            <div class="text-info">
                @if($shed->latestFlock?->count() > 0)
                    <strong>{{ $shed->latestFlock->name }}</strong><br>
                    Start Date: {{ $shed->latestFlock->start_date->format('d-m-Y') }}<br>
                    Flock Age: {{ $shed->latestFlock->age }} Days
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
                                    <a href="javascript:void(0)"
                                       class="p-2 border rounded me-2 edit-flock"
                                       title="Edit Flock"
                                       data-flock-id="{{ $flock->id }}"
                                       data-flock-name="{{ $flock->name }}">
                                        <i class="ti ti-edit"></i>
                                    </a>

                                    <a href="javascript:void(0);"
                                       title="Delete Flock"
                                       data-farm-id="{{ $flock->id }}"
                                       data-farm-name="{{ $flock->name }}"
                                       class="p-2 open-delete-modal">
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

<!-- Add Shed Modal -->
<div class="modal fade" id="addFlockModal" tabindex="-1" aria-labelledby="addShedModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.sheds.store') }}" method="POST"
                  class="needs-validation" novalidate>
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addShedModalLabel">Add Shed</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        {{-- Shed Name --}}
                        <div class="col-lg-6 mb-3">
                            <label for="name" class="form-label">Shed Name<span class="text-danger ms-1">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <div class="invalid-feedback">Shed name is required.</div>
                        </div>

                        {{-- Farm --}}
                        <div class="col-lg-6 mb-3">
                            <label for="farm_id" class="form-label">Farm<span class="text-danger ms-1">*</span></label>
                            <select class="form-select basic-select" id="farm_id" name="farm_id" required>
                                <option value="{{ $farm->id }}">{{ $farm->name }}</option>
                            </select>
                            <div class="invalid-feedback">Farm is required.</div>
                        </div>

                        {{-- Capacity --}}
                        <div class="col-lg-6 mb-3">
                            <label for="capacity" class="form-label">Capacity<span class="text-danger ms-1">*</span></label>
                            <input type="number" id="capacity" name="capacity" class="form-control" required>
                            <div class="invalid-feedback">Shed capacity is required to enter.</div>
                        </div>

                        {{-- Type --}}
                        <div class="col-lg-6 mb-3">
                            <label for="type" class="form-label">Shed Type<span class="text-danger ms-1">*</span></label>
                            <select class="form-select basic-select" id="type" name="type" required>
                                <option value="">Select Type</option>
                                @foreach($types as $t)
                                    <option value="{{ $t }}">{{ ucfirst($t) }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">Shed type is required.</div>
                        </div>

                        {{-- Description --}}
                        <div class="col-lg-12 mb-3">
                            <label for="description" class="form-label">Other Details<span class="text-danger ms-1">*</span></label>
                            <textarea class="form-control" id="description" name="description" rows="2"></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success me-2">Save Shed</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

