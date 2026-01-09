@php
    $currentExpense = $farmExpense ?? null;
    $selectedFarmId = old('farm_id', optional($currentExpense)->farm_id);
    $selectedShedId = old('shed_id', optional($currentExpense)->shed_id);
    $selectedFlockId = old('flock_id', optional($currentExpense)->flock_id);
@endphp

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="farm_id" class="form-label">Farm <span class="text-danger">*</span></label>
        <select class="form-select" name="farm_id" id="farm_id" required>
            <option value="">Select Farm</option>
            @foreach($farms as $farm)
                <option value="{{ $farm->id }}" {{ (string) $selectedFarmId === (string) $farm->id ? 'selected' : '' }}>
                    {{ $farm->name }}
                </option>
            @endforeach
        </select>
        <div class="invalid-feedback">Please select a farm.</div>
    </div>
    <div class="col-md-6 mb-3">
        <label for="expense_date" class="form-label">Expense Date <span class="text-danger">*</span></label>
        <input type="date" class="form-control" name="expense_date" id="expense_date"
               value="{{ old('expense_date', optional(optional($currentExpense)->expense_date)->format('Y-m-d')) }}" required>
        <div class="invalid-feedback">Please select expense date.</div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="shed_id" class="form-label">Shed (Optional)</label>
        <select class="form-select" name="shed_id" id="shed_id"
                data-api-base="{{ url('admin/farm-expenses/sheds-by-farm') }}"
                data-selected="{{ $selectedShedId }}">
            <option value="">Select Shed</option>
            @foreach($sheds as $shed)
                <option value="{{ $shed->id }}" {{ (string) $selectedShedId === (string) $shed->id ? 'selected' : '' }}>
                    {{ $shed->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6 mb-3">
        <label for="flock_id" class="form-label">Flock (Optional)</label>
        <select class="form-select" name="flock_id" id="flock_id"
                data-api-base="{{ url('admin/farm-expenses/flocks-by-shed') }}"
                data-selected="{{ $selectedFlockId }}">
            <option value="">Select Flock</option>
            @foreach($flocks as $flock)
                <option value="{{ $flock->id }}" {{ (string) $selectedFlockId === (string) $flock->id ? 'selected' : '' }}>
                    {{ $flock->name }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mb-3">
        <label for="expense_head_id" class="form-label">Expense Head <span class="text-danger">*</span></label>
        <select class="form-select" name="expense_head_id" id="expense_head_id" required>
            <option value="">Select Expense Head</option>
            @foreach($expenseHeads->groupBy('category') as $category => $items)
                <optgroup label="{{ $category }}">
                    @foreach($items as $item)
                        <option value="{{ $item->id }}" {{ (string) old('expense_head_id', optional($currentExpense)->expense_head_id) === (string) $item->id ? 'selected' : '' }}>
                            {{ $item->item }}
                        </option>
                    @endforeach
                </optgroup>
            @endforeach
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" name="description" id="description" rows="2">{{ old('description', optional($currentExpense)->description) }}</textarea>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label for="quantity" class="form-label">Quantity</label>
        <input type="number" step="0.001" class="form-control" name="quantity" id="quantity"
               value="{{ old('quantity', optional($currentExpense)->quantity) }}">
    </div>
    <div class="col-md-4 mb-3">
        <label for="unit" class="form-label">Unit</label>
        <input type="text" class="form-control" name="unit" id="unit" placeholder="kg, L, pack"
               value="{{ old('unit', optional($currentExpense)->unit) }}">
    </div>
    <div class="col-md-4 mb-3">
        <label for="unit_cost" class="form-label">Unit Cost (PKR)</label>
        <input type="number" step="0.01" class="form-control" name="unit_cost" id="unit_cost"
               value="{{ old('unit_cost', optional($currentExpense)->unit_cost) }}">
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="amount" class="form-label">Amount (PKR)</label>
        <input type="number" step="0.01" class="form-control" name="amount" id="amount"
               value="{{ old('amount', optional($currentExpense)->amount) }}">
        <small class="text-muted">Leave blank to auto-calculate from quantity x unit cost</small>
    </div>
    <div class="col-md-6 mb-3">
        <label for="reference_no" class="form-label">Reference No.</label>
        <input type="text" class="form-control" name="reference_no" id="reference_no"
               value="{{ old('reference_no', optional($currentExpense)->reference_no) }}">
    </div>
</div>
