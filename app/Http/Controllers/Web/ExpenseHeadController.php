<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ExpenseHead;
use Illuminate\Http\Request;

class ExpenseHeadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $expenseHeads = ExpenseHead::all();
        $categories = ExpenseHead::categories();

        return view(
            'admin.expense_heads.index',
            compact('expenseHeads', 'categories')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'item' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $validated['is_active'] = (isset($request->is_active)) ? true : false;

        ExpenseHead::create($validated);

        return redirect()
            ->route('expense.heads.index')
            ->with('success', 'Expense has been created successfully.');
    }

    public function show($id)
    {
        $expenseHead = ExpenseHead::findOrFail($id);
        return response()->json($expenseHead);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ExpenseHead $expenseHead)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'item' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $validated['is_active'] = (isset($request->is_active)) ? true : false;
        $expenseHead->update($validated);

        return redirect()
            ->route('expense.heads.index')
            ->with('success', 'Expense has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExpenseHead $expenseHead)
    {
        $expenseHead->delete();

        return redirect()
            ->route('expense.heads.index')
            ->with('success', 'Expense has been deleted successfully.');
    }

    public function toggle(ExpenseHead $expenseHead)
    {
        $toggle = ($expenseHead->is_active) ? 'blocked' : 'activated';
        $expenseHead->is_active = ! $expenseHead->is_active;
        $expenseHead->save();

        return redirect()->back()
            ->with('success', "Status of Expense: {$expenseHead->item} has been {$toggle} successfully.");
    }
}
