<!-- Create Partner Modal -->
<div class="modal fade" id="addPartnerModal" tabindex="-1" aria-labelledby="createPartnerLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <form action="{{ route('partners.store') }}" method="POST" class="needs-validation" novalidate enctype="multipart/form-data">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="createPartnerLabel">Add Partner</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    {{-- Global validation errors (server-side) --}}
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>There were some problems with your input:</strong>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="row g-3">
                        <!-- Name / Title -->
                        <div class="col-md-7">
                            <label class="form-label">Partner Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                            <div class="invalid-feedback">Please enter the partner name.</div>
                            @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <!-- Website URL -->
                        <div class="col-md-5">
                            <label class="form-label">Website</label>
                            <input type="url" name="website_url" class="form-control" placeholder="https://example.com" value="{{ old('website_url') }}">
                            @error('website_url') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <!-- Category/Group (optional; adjust to your schema) -->
                        <div class="col-md-6">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-select">
                                <option value="">Select category</option>
                                <option value="silicon" {{ old('category')=='silicon'?'selected':'' }}>Silicon / Hardware</option>
                                <option value="distribution" {{ old('category')=='distribution'?'selected':'' }}>Distribution</option>
                                <option value="ngo" {{ old('category')=='ngo'?'selected':'' }}>NGO / Development</option>
                                <option value="academia" {{ old('category')=='academia'?'selected':'' }}>Academia / Research</option>
                                <option value="engagement" {{ old('category')=='engagement'?'selected':'' }}>Engagement / UX</option>
                            </select>
                            @error('category') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <!-- Short Description -->
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" rows="3" class="form-control" placeholder="One or two lines about this partner...">{{ old('description') }}</textarea>
                            @error('description') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <!-- Logo upload -->
                        <div class="col-md-6">
                            <label class="form-label">Logo <span class="text-muted">(PNG/JPG/SVG)</span></label>
                            <input type="file" name="logo" id="partnerLogoInput" class="form-control" accept=".png,.jpg,.jpeg,.svg,image/*">
                            <div class="form-text">Recommended: transparent PNG/SVG, ≤ 1MB.</div>
                            @error('logo') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <!-- Live preview -->
                        <div class="col-md-6 d-flex align-items-end">
                            <div>
                                <div class="border rounded p-2 bg-light d-inline-block">
                                    <img id="partnerLogoPreview" src="{{ asset('assets/img/placeholders/logo-placeholder.svg') }}" alt="Logo preview" style="max-height:56px; max-width:220px;">
                                </div>
                                <div class="small text-muted mt-1">Logo preview</div>
                            </div>
                        </div>

                        <!-- (Optional) Highlights/Badges JSON -->
                        <div class="col-12">
                            <label class="form-label">Highlights (JSON, optional)</label>
                            <textarea name="highlights" rows="2" class="form-control" placeholder='e.g. ["Supply Assurance","DFM Reviews"]'>{{ old('highlights') }}</textarea>
                            @error('highlights') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save Partner</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('js')
    <script>
        // Bootstrap client-side validation
        (function () {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) { event.preventDefault(); event.stopPropagation(); }
                    form.classList.add('was-validated');
                }, false);
            });
        })();

        // Logo live preview
        document.getElementById('partnerLogoInput')?.addEventListener('change', function (e) {
            const file = e.target.files && e.target.files[0];
            if (!file) return;
            const url = URL.createObjectURL(file);
            const img = document.getElementById('partnerLogoPreview');
            img.src = url;
            img.onload = () => URL.revokeObjectURL(url);
        });
    </script>
@endpush
