<!-- Add Pricing Modal -->
<div class="modal fade" id="addPricingModal" tabindex="-1" aria-labelledby="addPricingModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('pricings.store') }}" class="needs-validation" novalidate method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addPricingModalLabel">Add Pricing Plan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <label for="pricing-name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="pricing-name" name="name" required>
                            <div class="invalid-feedback">Name is required.</div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label for="pricing-currency" class="form-label">Currency <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="pricing-currency" name="currency" value="USD" maxlength="10" required>
                            <div class="invalid-feedback">Currency is required.</div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label for="pricing-price" class="form-label">Price <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="pricing-price" name="price" min="0" step="0.01" required>
                            <div class="invalid-feedback">Price is required.</div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label for="pricing-billing-interval" class="form-label">Billing Interval <span class="text-danger">*</span></label>
                            <select class="form-select" id="pricing-billing-interval" name="billing_interval" required>
                                <option value="monthly">Monthly</option>
                                <option value="yearly">Yearly</option>
                                <option value="weekly">Weekly</option>
                                <option value="one_time">One Time</option>
                            </select>
                            <div class="invalid-feedback">Billing interval is required.</div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label for="pricing-trial-period" class="form-label">Trial Period (days)</label>
                            <input type="number" class="form-control" id="pricing-trial-period" name="trial_period_days" min="0" value="0">
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label for="pricing-sort-order" class="form-label">Sort Order</label>
                            <input type="number" class="form-control" id="pricing-sort-order" name="sort_order" min="0" value="0">
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label for="pricing-description" class="form-label">Description</label>
                            <textarea class="form-control" id="pricing-description" name="description" rows="2"></textarea>
                        </div>
                        <div class="col-lg-12 mb-3 border rounded p-3">
                            <div class="row">
                                <div class="col-lg-2 mb-2">
                                    <label class="form-label">Max Farms</label>
                                    <input type="number" class="form-control" name="max_farms" min="1" value="1" required>
                                </div>
                                <div class="col-lg-2 mb-2">
                                    <label class="form-label">Max Sheds</label>
                                    <input type="number" class="form-control" name="max_sheds" min="1" value="1" required>
                                </div>
                                <div class="col-lg-2 mb-2">
                                    <label class="form-label">Max Flocks</label>
                                    <input type="number" class="form-control" name="max_flocks" min="1" value="1" required>
                                </div>
                                <div class="col-lg-2 mb-2">
                                    <label class="form-label">Max Devices</label>
                                    <input type="number" class="form-control" name="max_devices" min="1" value="1" required>
                                </div>
                                <div class="col-lg-2 mb-2">
                                    <label class="form-label">Max Users</label>
                                    <input type="number" class="form-control" name="max_users" min="1" value="1" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-3 border rounded p-3">
                            <label class="form-label mb-2">Feature Access</label>
                            <div class="form-check form-switch mb-1">
                                <input class="form-check-input" type="checkbox" id="feature-auto-control" name="feature_flags[auto_control]" value="1">
                                <label class="form-check-label" for="feature-auto-control">Automated Control</label>
                            </div>
                            <div class="form-check form-switch mb-1">
                                <input class="form-check-input" type="checkbox" id="feature-reporting" name="feature_flags[reporting]" value="1">
                                <label class="form-check-label" for="feature-reporting">Reporting</label>
                            </div>
                            <div class="form-check form-switch mb-1">
                                <input class="form-check-input" type="checkbox" id="feature-analytics" name="feature_flags[analytics]" value="1">
                                <label class="form-check-label" for="feature-analytics">Historical Analytics</label>
                            </div>
                            <div class="mb-2">
                                <label class="form-label mt-2" for="feature-support">Support Level</label>
                                <select class="form-select" id="feature-support" name="feature_flags[support]">
                                    <option value="none">None</option>
                                    <option value="email">Email</option>
                                    <option value="priority_email">Priority Email</option>
                                    <option value="24/7_phone">24/7 Phone</option>
                                </select>
                            </div>
                            <div class="mb-2">
                                <label class="form-label mt-2" for="feature-history-days">Data History (days)</label>
                                <input type="number" class="form-control" id="feature-history-days" name="feature_flags[history_days]" min="0" value="30">
                            </div>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="pricing-active" name="is_active" value="1" checked>
                                <label class="form-check-label" for="pricing-active">Active</label>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label for="pricing-meta" class="form-label">Meta (optional, JSON)</label>
                            <textarea class="form-control" id="pricing-meta" name="meta" rows="2" placeholder='{"badge": "Popular"}'></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save Plan</button>
                </div>
            </form>
        </div>
    </div>
</div>
