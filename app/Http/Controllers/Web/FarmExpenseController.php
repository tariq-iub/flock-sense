<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ExpenseHead;
use App\Models\Farm;
use App\Models\FarmExpense;
use App\Models\Flock;
use App\Models\Shed;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class FarmExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // --- Normalize filters: support BOTH plain params and Spatie `filter[...]`
        $filters = array_filter([
            'farm_id' => $request->input('farm_id'),
            'shed_id' => $request->input('shed_id'),
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
        ], fn ($v) => ! is_null($v) && $v !== '');

        $request->merge([
            'filter' => array_merge($request->input('filter', []), $filters),
        ]);

        // --- Build accessible farms query ONCE (used for dropdown + scoping)
        $farmsQuery = Farm::query()
            ->select(['id', 'name', 'owner_id'])
            ->orderBy('name');

        if ($user->hasRole('admin')) {
            // no restriction
        } elseif ($user->hasRole('owner')) {
            $farmsQuery->where('owner_id', $user->id);
        } elseif ($user->hasRole('manager')) {
            // Avoid pluck()->toArray(); keep it as a subquery
            $farmsQuery->whereIn('id', $user->managedFarms()->select('farms.id'));
        } else {
            $expenses = collect();
            $farms = collect();
            $sheds = collect();
            $expenseHeads = ExpenseHead::where('is_active', true)
                ->orderBy('category')->orderBy('item')
                ->get();
            $expenseHeadGroups = $expenseHeads->groupBy('category');

            return view('admin.farm_expenses.index', compact('expenses', 'farms', 'expenseHeads', 'expenseHeadGroups', 'sheds'));
        }

        $farms = $farmsQuery->get();
        $farmIds = $farms->pluck('id');

        // Selected farm from either filter[farm_id] or farm_id
        $selectedFarmId = $request->input('filter.farm_id');

        // Optional: if a farm_id is selected, ensure it's accessible (prevents probing IDs)
        if ($selectedFarmId && ! $farmIds->contains((int) $selectedFarmId)) {
            $selectedFarmId = null;
            // You can also choose: abort(403);
            // abort(403, 'You do not have access to this farm.');
        }

        // --- Expense Heads (dropdown)
        $expenseHeads = ExpenseHead::query()
            ->where('is_active', true)
            ->orderBy('category')
            ->orderBy('item')
            ->get();
        $expenseHeadGroups = $expenseHeads->groupBy('category');

        // --- Sheds (dropdown) limited to accessible farms, optionally to selected farm
        $sheds = Shed::query()
            ->select(['id', 'farm_id', 'name'])
            ->whereIn('farm_id', $farmIds)
            ->when($selectedFarmId, fn ($q) => $q->where('farm_id', $selectedFarmId))
            ->orderBy('name')
            ->get();

        $flocks = Flock::query()
            ->select(['id', 'shed_id', 'name'])
            ->whereIn('shed_id', $sheds->pluck('id'))
            ->orderBy('name')
            ->get();

        // --- Pagination guard
        $perPage = (int) $request->input('per_page', 15);
        $perPage = max(5, min($perPage, 100));

        // --- Expenses query via Spatie Query Builder
        $baseExpensesQuery = FarmExpense::query()
            ->with([
                'farm:id,name,owner_id',
                'shed:id,farm_id,name',
                'flock:id,shed_id,name,chicken_count,start_date',
                'head:id,category,item',
                'creator:id,name',
            ])
            ->when(! $user->hasRole('admin'), fn ($q) => $q->whereIn('farm_id', $farmIds));

        $expenses = QueryBuilder::for($baseExpensesQuery)
            ->allowedFilters([
                AllowedFilter::exact('farm_id'),
                AllowedFilter::exact('shed_id'),

                // Range filters (keep names compatible with your UI)
                AllowedFilter::callback('date_from', function ($query, $value) {
                    $query->whereDate('expense_date', '>=', $value);
                }),
                AllowedFilter::callback('date_to', function ($query, $value) {
                    $query->whereDate('expense_date', '<=', $value);
                }),

                // Optional extras (keep/remove as you like)
                AllowedFilter::exact('expense_head_id'),
                AllowedFilter::partial('remarks'),
            ])
            ->allowedSorts(['expense_date', 'amount', 'created_at'])
            ->defaultSort('-expense_date')
            ->paginate($perPage)
            ->withQueryString();

        return view(
            'admin.farm_expenses.index',
            compact('expenses', 'farms', 'expenseHeads', 'expenseHeadGroups', 'sheds', 'flocks')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $user = auth()->user();

        if (! $user->hasAnyRole(['admin', 'owner', 'manager'])) {
            abort(403);
        }

        $farms = $this->getAccessibleFarms($user);
        $expenseHeads = ExpenseHead::where('is_active', true)
            ->orderBy('category')
            ->orderBy('item')
            ->get();

        $selectedFarmId = old('farm_id') ?? $request->input('farm_id');
        $selectedShedId = old('shed_id') ?? $request->input('shed_id');

        $sheds = $selectedFarmId
            ? Shed::where('farm_id', $selectedFarmId)->get()
            : collect([]);

        $flocks = $selectedShedId
            ? Flock::where('shed_id', $selectedShedId)->get()
            : collect([]);

        return view('admin.farm_expenses.create', compact('expenseHeads', 'farms', 'sheds', 'flocks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'farm_id' => 'required|exists:farms,id',
            'shed_id' => 'nullable|exists:sheds,id',
            'flock_id' => 'nullable|exists:flocks,id',
            'expense_head_id' => 'required|exists:expense_heads,id',
            'expense_date' => 'required|date',
            'description' => 'nullable|string|max:255',
            'quantity' => 'nullable|numeric|min:0',
            'unit' => 'nullable|string|max:20',
            'unit_cost' => 'nullable|numeric|min:0',
            'amount' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'vendor_id' => 'nullable|integer',
            'reference_no' => 'nullable|string|max:255',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['currency'] = $validated['currency'] ?? 'PKR';

        FarmExpense::create($validated);

        return redirect()
            ->route('farm.expenses.index')
            ->with('success', 'Farm expense has been created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(FarmExpense $farmExpense)
    {
        return response()->json(
            $farmExpense->load(['farm', 'shed', 'flock', 'head', 'creator'])
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FarmExpense $farmExpense)
    {
        $user = auth()->user();

        if (! $user->hasAnyRole(['admin', 'owner', 'manager'])) {
            abort(403);
        }

        $farms = $this->getAccessibleFarms($user);
        $accessibleFarmIds = $farms->pluck('id');

        if (! $user->hasRole('admin') && ! $accessibleFarmIds->contains($farmExpense->farm_id)) {
            abort(403);
        }

        $expenseHeads = ExpenseHead::where('is_active', true)
            ->orderBy('category')
            ->orderBy('item')
            ->get();

        $sheds = $farmExpense->farm_id
            ? Shed::where('farm_id', $farmExpense->farm_id)->get()
            : collect([]);

        $flocks = $farmExpense->shed_id
            ? Flock::where('shed_id', $farmExpense->shed_id)->get()
            : collect([]);

        return view('admin.farm_expenses.edit', compact('expenseHeads', 'farms', 'farmExpense', 'sheds', 'flocks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FarmExpense $farmExpense)
    {
        $validated = $request->validate([
            'farm_id' => 'required|exists:farms,id',
            'shed_id' => 'nullable|exists:sheds,id',
            'flock_id' => 'nullable|exists:flocks,id',
            'expense_head_id' => 'required|exists:expense_heads,id',
            'expense_date' => 'required|date',
            'description' => 'nullable|string|max:255',
            'quantity' => 'nullable|numeric|min:0',
            'unit' => 'nullable|string|max:20',
            'unit_cost' => 'nullable|numeric|min:0',
            'amount' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'vendor_id' => 'nullable|integer',
            'reference_no' => 'nullable|string|max:255',
        ]);

        $validated['currency'] = $validated['currency'] ?? 'PKR';

        $farmExpense->update($validated);

        return redirect()
            ->route('farm.expenses.index')
            ->with('success', 'Farm expense has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FarmExpense $farmExpense)
    {
        $user = auth()->user();

        if (! $user->hasAnyRole(['admin', 'owner'])) {
            abort(403, 'You are not authorized to delete farm expenses.');
        }

        $farmExpense->delete();

        return redirect()
            ->route('farm.expenses.index')
            ->with('success', 'Farm expense has been deleted successfully.');
    }

    /**
     * Get sheds by farm
     */
    public function getShedsByFarm($farmId)
    {
        $sheds = Shed::where('farm_id', $farmId)->get(['id', 'name']);

        return response()->json($sheds);
    }

    /**
     * Get flocks by shed
     */
    public function getFlocksByShed($shedId)
    {
        $flocks = Flock::where('shed_id', $shedId)->get(['id', 'name', 'start_date', 'end_date']);

        return response()->json($flocks);
    }

    /**
     * Get farms accessible to the authenticated user.
     */
    protected function getAccessibleFarms($user)
    {
        if ($user->hasRole('admin')) {
            return Farm::all();
        }

        if ($user->hasRole('owner')) {
            return Farm::where('owner_id', $user->id)->get();
        }

        if ($user->hasRole('manager')) {
            $managedFarmIds = $user->managedFarms()->pluck('id')->toArray();

            return Farm::whereIn('id', $managedFarmIds)->get();
        }

        return collect([]);
    }
}
