<!-- Edit Pricing Modal -->
<div class="modal fade" id="editPricingModal" tabindex="-1" aria-labelledby="editPricingModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editPricingForm" action="" class="needs-validation" novalidate method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editPricingModalLabel">Edit Pricing Plan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit-pricing-id" name="id" value="">
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <label for="edit-pricing-name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit-pricing-name" name="name" required>
                            <div class="invalid-feedback">Name is required.</div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label for="edit-pricing-currency" class="form-label">Currency <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit-pricing-currency" name="currency" maxlength="10" required>
                            <div class="invalid-feedback">Currency is required.</div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label for="edit-pricing-price" class="form-label">Price <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="edit-pricing-price" name="price" min="0" step="0.01" required>
                            <div class="invalid-feedback">Price is required.</div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label for="edit-pricing-billing-interval" class="form-label">Billing Interval <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit-pricing-billing-interval" name="billing_interval" required>
                                <option value="monthly">Monthly</option>
                                <option value="yearly">Yearly</option>
                                <option value="weekly">Weekly</option>
                                <option value="one_time">One Time</option>
                            </select>
                            <div class="invalid-feedback">Billing interval is required.</div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label for="edit-pricing-trial-period" class="form-label">Trial Period (days)</label>
                            <input type="number" class="form-control" id="edit-pricing-trial-period" name="trial_period_days" min="0">
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label for="edit-pricing-sort-order" class="form-label">Sort Order</label>
                            <input type="number" class="form-control" id="edit-pricing-sort-order" name="sort_order" min="0">
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label for="edit-pricing-description" class="form-label">Description</label>
                            <textarea class="form-control" id="edit-pricing-description" name="description" rows="2"></textarea>
                        </div>
                        <div class="col-lg-12 mb-3 border rounded p-3">
                            <div class="row">
                                <div class="col-lg-2 mb-2">
                                    <label class="form-label">Max Farms</label>
                                    <input type="number" class="form-control" id="edit-max-farms" name="max_farms" min="1" required>
                                </div>
                                <div class="col-lg-2 mb-2">
                                    <label class="form-label">Max Sheds</label>
                                    <input type="number" class="form-control" id="edit-max-sheds" name="max_sheds" min="1" required>
                                </div>
                                <div class="col-lg-2 mb-2">
                                    <label class="form-label">Max Flocks</label>
                                    <input type="number" class="form-control" id="edit-max-flocks" name="max_flocks" min="1" required>
                                </div>
                                <div class="col-lg-2 mb-2">
                                    <label class="form-label">Max Devices</label>
                                    <input type="number" class="form-control" id="edit-max-devices" name="max_devices" min="1" required>
                                </div>
                                <div class="col-lg-2 mb-2">
                                    <label class="form-label">Max Users</label>
                                    <input type="number" class="form-control" id="edit-max-users" name="max_users" min="1" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-3 border rounded p-3">
                            <label class="form-label mb-2">Feature Access</label>
                            <div class="form-check form-switch mb-1">
                                <input class="form-check-input" type="checkbox" id="edit-feature-auto-control" name="feature_flags[auto_control]" value="1">
                                <label class="form-check-label" for="edit-feature-auto-control">Automated Control</label>
                            </div>
                            <div class="form-check form-switch mb-1">
                                <input class="form-check-input" type="checkbox" id="edit-feature-reporting" name="feature_flags[reporting]" value="1">
                                <label class="form-check-label" for="edit-feature-reporting">Reporting</label>
                            </div>
                            <div class="form-check form-switch mb-1">
                                <input class="form-check-input" type="checkbox" id="edit-feature-analytics" name="feature_flags[analytics]" value="1">
                                <label class="form-check-label" for="edit-feature-analytics">Historical Analytics</label>
                            </div>
                            <div class="mb-2">
                                <label class="form-label mt-2" for="edit-feature-support">Support Level</label>
                                <select class="form-select" id="edit-feature-support" name="feature_flags[support]">
                                    <option value="none">None</option>
                                    <option value="email">Email</option>
                                    <option value="priority_email">Priority Email</option>
                                    <option value="24/7_phone">24/7 Phone</option>
                                </select>
                            </div>
                            <div class="mb-2">
                                <label class="form-label mt-2" for="edit-feature-history-days">Data History (days)</label>
                                <input type="number" class="form-control" id="edit-feature-history-days" name="feature_flags[history_days]" min="0">
                            </div>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="edit-pricing-active" name="is_active" value="1">
                                <label class="form-check-label" for="edit-pricing-active">Active</label>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label for="edit-pricing-meta" class="form-label">Meta (optional, JSON)</label>
                            <textarea class="form-control" id="edit-pricing-meta" name="meta" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Update Plan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('js')
    <script>
        /**
         * Prefill the Edit Pricing Modal with pricing plan data.
         * @param {Object} plan - The pricing plan data (from API or your DB).
         * @param {string} updateUrl - The URL to submit the PUT request to (e.g. '/admin/pricings/5')
         */
        function fillEditPricingModal(plan, updateUrl) {
            // Set form action
            $('#editPricingForm').attr('action', updateUrl);

            // Set main fields
            $('#edit-pricing-id').val(plan.id || '');
            $('#edit-pricing-name').val(plan.name || '');
            $('#edit-pricing-currency').val(plan.currency || '');
            $('#edit-pricing-price').val(plan.price || 0);
            $('#edit-pricing-billing-interval').val(plan.billing_interval || 'monthly');
            $('#edit-pricing-trial-period').val(plan.trial_period_days || 0);
            $('#edit-pricing-sort-order').val(plan.sort_order || 0);
            $('#edit-pricing-description').val(plan.description || '');

            // Tiered limits
            $('#edit-max-farms').val(plan.max_farms || 1);
            $('#edit-max-sheds').val(plan.max_sheds || 1);
            $('#edit-max-flocks').val(plan.max_flocks || 1);
            $('#edit-max-devices').val(plan.max_devices || 1);
            $('#edit-max-users').val(plan.max_users || 1);

            // Feature flags (handle as object)
            const ff = plan.feature_flags || {};

            $('#edit-feature-auto-control').prop('checked', !!ff.auto_control);
            $('#edit-feature-reporting').prop('checked', !!ff.reporting);
            $('#edit-feature-analytics').prop('checked', !!ff.analytics);

            // Support level select
            $('#edit-feature-support').val(ff.support || 'none');
            // Data history
            $('#edit-feature-history-days').val(ff.history_days || 0);

            // Active status
            $('#edit-pricing-active').prop('checked', !!plan.is_active);

            // Meta (show as formatted JSON if present)
            let metaString = '';
            if (typeof plan.meta === 'object' && plan.meta !== null) {
                metaString = JSON.stringify(plan.meta, null, 2);
            } else if (typeof plan.meta === 'string') {
                metaString = plan.meta;
            }
            $('#edit-pricing-meta').val(metaString);
        }
    </script>
@endpush
