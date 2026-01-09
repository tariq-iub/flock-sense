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
                            <label class="form-label">Company Name <span class="text-danger">*</span></label>
                            <input type="text" name="company_name" class="form-control" value="{{ old('company_name') }}" required>
                            <div class="invalid-feedback">Please enter the company name.</div>
                            @error('company_name') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <!-- Website URL -->
                        <div class="col-md-5">
                            <label class="form-label">Website</label>
                            <input type="url" name="url" class="form-control" value="{{ old('url') }}">
                            @error('url') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <!-- Logo upload -->
                        <div class="col-md-7">
                            <label class="form-label">Logo <span class="text-muted">(PNG/JPG/SVG)</span></label>
                            <input type="file" name="logo" id="partnerLogoInput" class="form-control" accept=".png,.jpg,.jpeg,.svg,image/*">
                            <div class="form-text">Recommended: transparent PNG/SVG, ≤ 1MB.</div>
                            @error('logo') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <!-- Live preview -->
                        @php
                            $partner_logo = 'assets/img/partner-placeholder.png';
                        @endphp
                        <div class="col-md-5 d-flex align-items-end">
                            <div>
                                <div class="border rounded p-2 bg-light d-inline-block">
                                    <img id="partnerLogoPreview" src="{{ asset($partner_logo) }}" alt="Logo preview" style="max-height:56px; max-width:220px;">
                                </div>
                                <div class="small text-muted mt-1">Logo preview</div>
                            </div>
                        </div>

                        <!-- Partner Introduction -->
                        <div class="col-12">
                            <label class="form-label">Partner Introduction</label>
                            <textarea name="introduction" rows="3" class="form-control"
                                      placeholder="Partner's introduction...">{{ old('introduction') }}</textarea>
                            @error('introduction') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <!-- Short Description -->
                        <div class="col-12">
                            <label class="form-label">Partnership Detail</label>
                            <textarea name="partnership_detail" rows="3" class="form-control"
                                      placeholder="Partnership detail...">{{ old('partnership_detail') }}</textarea>
                            @error('partnership_detail') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <!-- (Optional) Keywords -->
                        <div class="col-12">
                            <label class="form-label">Keywords</label>

                            <input
                                type="text"
                                name="support_keywords_display"
                                id="support_keywords_display"
                                class="form-control"
                                data-role="tagsinput"
                                placeholder="Add keywords and press Enter"
                            >

                            {{-- Hidden real JSON field submitted to backend --}}
                            <input type="hidden" name="support_keywords" id="support_keywords">

                            @error('support_keywords') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success me-2">Save Partner</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

        // Initialize tagsinput for keywords
        $(document).ready(function() {
            $('#support_keywords_display').tagsinput({
                confirmKeys: [13, 188],
                trimValue: true
            });

            // Sync tags to hidden field on add/remove
            $('#support_keywords_display').on('itemAdded itemRemoved', function() {
                const tags = $(this).tagsinput('items');
                $('#support_keywords').val(JSON.stringify(tags));
            });
        });
    </script>
@endpush
