<!-- Add Partner Modal -->
<div class="modal fade" id="addPartnerModal" tabindex="-1" aria-labelledby="addPartnerModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('partners.store') }}" method="POST" class="needs-validation" novalidate enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addPartnerModalLabel">Add Partner</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Company Name -->
                        <div class="col-lg-6">
                            <label for="partner-company-name" class="form-label">Company Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="partner-company-name" name="company_name" required>
                            <div class="invalid-feedback">Company name is required.</div>
                        </div>

                        <!-- Website URL -->
                        <div class="col-lg-6">
                            <label for="partner-website" class="form-label">Website <span class="text-danger">*</span></label>
                            <input type="url" class="form-control" id="partner-website" name="website_url" placeholder="https://example.com" required>
                            <div class="invalid-feedback">Valid website URL is required.</div>
                        </div>

                        <!-- Logo Upload + Preview -->
                        <div class="col-lg-6">
                            <label for="partner-logo" class="form-label">Logo <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="partner-logo" name="logo" accept="image/*" required>
                            <div class="invalid-feedback">Partner logo is required.</div>
                            <div class="mt-2">
                                <img id="partner-logo-preview" src="" alt="Logo preview" class="img-thumbnail d-none" style="max-height: 80px;">
                            </div>
                            <small class="text-muted">PNG/JPG/SVG, up to 2MB.</small>
                        </div>

                        <!-- Sort Order -->
                        <div class="col-lg-3">
                            <label for="partner-sort-order" class="form-label">Sort Order</label>
                            <input type="number" class="form-control" id="partner-sort-order" name="sort_order" min="0" value="0">
                        </div>

                        <!-- Active -->
                        <div class="col-lg-3">
                            <label for="partner-active" class="form-label mb-3">Partner Status</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="partner-active" name="is_active" value="1" checked>
                                <label class="form-check-label" for="partner-active">Active</label>
                            </div>
                        </div>

                        <!-- Summary -->
                        <div class="col-12">
                            <label for="partner-summary" class="form-label">Summary / Description</label>
                            <textarea class="form-control" id="partner-summary" name="summary" rows="2" placeholder="Short description of the partnership, scope and value."></textarea>
                        </div>

                        <!-- Tags / Badges (stored as JSON array server-side) -->
                        <div class="col-12">
                            <label for="partner-tags" class="form-label">Badges / Tags</label>
                            <input type="text" class="form-control" id="partner-tags" name="tags" placeholder="Comma-separated e.g. Supply Assurance, DFM Reviews, Global Logistics">
                            <small class="text-muted">Use commas to separate multiple tags. Will be saved as JSON array.</small>
                        </div>

                        <!-- Highlights (bulleted points; saved as JSON array) -->
                        <div class="col-12 border rounded p-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label mb-0">Highlights</label>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="add-highlight">Add Row</button>
                            </div>
                            <div id="highlights-wrapper" class="vstack gap-2">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="highlights[]" placeholder="e.g. Secure provisioning for on-farm devices">
                                    <button class="btn btn-outline-danger remove-highlight" type="button"><i class="fa-solid fa-xmark"></i></button>
                                </div>
                            </div>
                            <small class="text-muted">Add 1–5 short bullets; saved as JSON array.</small>
                        </div>

                        <!-- Metrics (key/value pairs; saved as JSON object) -->
                        <div class="col-12 border rounded p-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label mb-0">Metrics</label>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="add-metric">Add Metric</button>
                            </div>
                            <div id="metrics-wrapper" class="vstack gap-2">
                                <div class="row g-2 align-items-center metric-row">
                                    <div class="col-5">
                                        <input type="text" class="form-control" name="metrics[key][]" placeholder="e.g. MTBF">
                                    </div>
                                    <div class="col-5">
                                        <input type="text" class="form-control" name="metrics[value][]" placeholder="e.g. 78k hrs">
                                    </div>
                                    <div class="col-2 text-end">
                                        <button class="btn btn-outline-danger remove-metric" type="button"><i class="fa-solid fa-xmark"></i></button>
                                    </div>
                                </div>
                            </div>
                            <small class="text-muted">Add key/value KPIs (e.g., “BOM Cost Delta” → “−7–12%”).</small>
                        </div>

                        <!-- Optional: Program / Category (if exists in schema) -->
                        <div class="col-lg-6">
                            <label for="partner-category" class="form-label">Category (optional)</label>
                            <select id="partner-category" class="form-select" name="category">
                                <option value="">— Select —</option>
                                <option value="silicon">Silicon / Hardware</option>
                                <option value="distribution">Distribution</option>
                                <option value="development">Development Programme</option>
                                <option value="research">Research / Academia</option>
                                <option value="engagement">Engagement / UX</option>
                            </select>
                        </div>

                        <!-- Optional: Contact Email -->
                        <div class="col-lg-6">
                            <label for="partner-contact-email" class="form-label">Contact Email (optional)</label>
                            <input type="email" class="form-control" id="partner-contact-email" name="contact_email" placeholder="partner@domain.com">
                        </div>

                        <!-- Meta JSON -->
                        <div class="col-12">
                            <label for="partner-meta" class="form-label">Meta (JSON, optional)</label>
                            <textarea class="form-control" id="partner-meta" name="meta" rows="2" placeholder='{"program":"Pilot","badge":"Featured"}'></textarea>
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
        (function() {
            'use strict';

            // Bootstrap validation
            document.addEventListener('submit', function(e) {
                const form = e.target.closest('form.needs-validation');
                if (!form) return;
                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                form.classList.add('was-validated');
            }, true);

            // Logo preview
            const logoInput = document.getElementById('partner-logo');
            const logoPreview = document.getElementById('partner-logo-preview');
            if (logoInput) {
                logoInput.addEventListener('change', function() {
                    const file = this.files && this.files[0];
                    if (!file) return;
                    const reader = new FileReader();
                    reader.onload = e => {
                        logoPreview.src = e.target.result;
                        logoPreview.classList.remove('d-none');
                    };
                    reader.readAsDataURL(file);
                });
            }

            // Highlights repeater
            const highlightsWrapper = document.getElementById('highlights-wrapper');
            const addHighlightBtn = document.getElementById('add-highlight');
            if (addHighlightBtn) {
                addHighlightBtn.addEventListener('click', function() {
                    const group = document.createElement('div');
                    group.className = 'input-group';
                    group.innerHTML = `
        <input type="text" class="form-control" name="highlights[]" placeholder="e.g. RMA and FRU programs">
        <button class="btn btn-outline-danger remove-highlight" type="button"><i class="fa-solid fa-xmark"></i></button>
      `;
                    highlightsWrapper.appendChild(group);
                });
            }
            highlightsWrapper?.addEventListener('click', function(e) {
                if (e.target.closest('.remove-highlight')) {
                    const row = e.target.closest('.input-group');
                    row?.remove();
                }
            });

            // Metrics repeater
            const metricsWrapper = document.getElementById('metrics-wrapper');
            const addMetricBtn = document.getElementById('add-metric');
            if (addMetricBtn) {
                addMetricBtn.addEventListener('click', function() {
                    const row = document.createElement('div');
                    row.className = 'row g-2 align-items-center metric-row';
                    row.innerHTML = `
        <div class="col-5"><input type="text" class="form-control" name="metrics[key][]" placeholder="Metric"></div>
        <div class="col-5"><input type="text" class="form-control" name="metrics[value][]" placeholder="Value"></div>
        <div class="col-2 text-end"><button class="btn btn-outline-danger remove-metric" type="button"><i class="fa-solid fa-xmark"></i></button></div>
      `;
                    metricsWrapper.appendChild(row);
                });
            }
            metricsWrapper?.addEventListener('click', function(e) {
                if (e.target.closest('.remove-metric')) {
                    e.target.closest('.metric-row')?.remove();
                }
            });

        })();
    </script>
@endpush
