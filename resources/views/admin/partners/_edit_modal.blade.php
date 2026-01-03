<!-- Edit Partner Modal -->
<div class="modal fade" id="editPartnerModal" tabindex="-1" aria-labelledby="editPartnerModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <form id="editPartnerForm" action="" class="needs-validation" novalidate method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title" id="editPartnerModalLabel">Edit Partner</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="edit-partner-id" name="id" value="">

                    <div class="row g-3">
                        <!-- Company Name -->
                        <div class="col-md-7">
                            <label for="edit-company-name" class="form-label">Company Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit-company-name" name="company_name" required>
                            <div class="invalid-feedback">Please enter the company name.</div>
                        </div>

                        <!-- Website URL -->
                        <div class="col-md-5">
                            <label for="edit-url" class="form-label">Website</label>
                            <input type="url" class="form-control" id="edit-url" name="url">
                        </div>

                        <!-- Logo upload -->
                        <div class="col-md-7">
                            <label class="form-label">Logo <span class="text-muted">(PNG/JPG/SVG)</span></label>
                            <input type="file" name="logo" id="editPartnerLogoInput" class="form-control" accept=".png,.jpg,.jpeg,.svg,image/*">
                            <div class="form-text">Recommended: transparent PNG/SVG, ≤ 1MB. Leave empty to keep current logo.</div>
                        </div>

                        <!-- Live preview -->
                        <div class="col-md-5 d-flex align-items-end">
                            <div>
                                <div class="border rounded p-2 bg-light d-inline-block">
                                    <img id="editPartnerLogoPreview" src="" alt="Logo preview" style="max-height:56px; max-width:220px;">
                                </div>
                                <div class="small text-muted mt-1">Current logo</div>
                            </div>
                        </div>

                        <!-- Partner Introduction -->
                        <div class="col-12">
                            <label for="edit-introduction" class="form-label">Partner Introduction</label>
                            <textarea name="introduction" id="edit-introduction" rows="3" class="form-control" placeholder="Partner's introduction..."></textarea>
                        </div>

                        <!-- Partnership Detail -->
                        <div class="col-12">
                            <label for="edit-partnership-detail" class="form-label">Partnership Detail</label>
                            <textarea name="partnership_detail" id="edit-partnership-detail" rows="3" class="form-control" placeholder="Partnership detail..."></textarea>
                        </div>

                        <!-- Keywords -->
                        <div class="col-12">
                            <label class="form-label">Keywords</label>
                            <input
                                type="text"
                                name="support_keywords_display"
                                id="edit_support_keywords_display"
                                class="form-control"
                                data-role="tagsinput"
                                placeholder="Add keywords and press Enter"
                            >
                            <input type="hidden" name="support_keywords" id="edit_support_keywords">
                        </div>

                        <!-- Sort Order -->
                        <div class="col-md-6">
                            <label for="edit-sort-order" class="form-label">Sort Order</label>
                            <input type="number" class="form-control" id="edit-sort-order" name="sort_order" min="0" value="0">
                        </div>

                        <!-- Active Status -->
                        <div class="col-md-6">
                            <label class="form-label d-block">Status</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" id="edit-is-active" name="is_active" value="1">
                                <label class="form-check-label" for="edit-is-active">Active</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success me-2">Update Partner</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('js')
    <script>
        /**
         * Prefill the Edit Partner Modal with partner data.
         * @param {Object} partner - The partner data (from API or your DB).
         * @param {string} updateUrl - The URL to submit the PUT request to (e.g. '/admin/partners/5')
         */
        function fillEditPartnerModal(partner, updateUrl) {
            // Set form action
            $('#editPartnerForm').attr('action', updateUrl);

            // Set main fields
            $('#edit-partner-id').val(partner.id || '');
            $('#edit-company-name').val(partner.company_name || '');
            $('#edit-url').val(partner.url || '');
            $('#edit-introduction').val(partner.introduction || '');
            $('#edit-partnership-detail').val(partner.partnership_detail || '');
            $('#edit-sort-order').val(partner.sort_order || 0);

            // Active status
            $('#edit-is-active').prop('checked', !!partner.is_active);

            // Logo preview
            const logoUrl = partner.logo_url || '{{ asset("assets/img/partner-placeholder.png") }}';
            $('#editPartnerLogoPreview').attr('src', logoUrl);

            // Keywords - Initialize tagsinput
            const $editTagsInput = $('#edit_support_keywords_display');

            // Destroy existing tagsinput if present
            if ($editTagsInput.data('tagsinput')) {
                $editTagsInput.tagsinput('destroy');
            }

            // Re-initialize
            $editTagsInput.tagsinput({
                confirmKeys: [13, 188],
                trimValue: true
            });

            // Clear and add keywords
            $editTagsInput.tagsinput('removeAll');
            if (partner.support_keywords && Array.isArray(partner.support_keywords)) {
                partner.support_keywords.forEach(function(keyword) {
                    $editTagsInput.tagsinput('add', keyword);
                });
            }

            // Sync to hidden field
            $('#edit_support_keywords').val(JSON.stringify(partner.support_keywords || []));

            // Update hidden field on change
            $editTagsInput.on('itemAdded itemRemoved', function() {
                const tags = $(this).tagsinput('items');
                $('#edit_support_keywords').val(JSON.stringify(tags));
            });
        }

        // Logo live preview for edit modal
        $(document).ready(function() {
            $('#editPartnerLogoInput').on('change', function(e) {
                const file = e.target.files && e.target.files[0];
                if (!file) return;
                const url = URL.createObjectURL(file);
                const img = document.getElementById('editPartnerLogoPreview');
                img.src = url;
                img.onload = () => URL.revokeObjectURL(url);
            });
        });
    </script>
@endpush
