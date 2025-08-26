<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $expenses = Expense::all();
        $categories = Expense::categories();

        return view(
            'admin.expenses.index',
            compact('expenses', 'categories')
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

        Expense::create($validated);

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Expense has been created successfully.');
    }

    public function show($id)
    {
        $expense = Expense::findOrFail($id);
        return response()->json($expense);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'item' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $validated['is_active'] = (isset($request->is_active)) ? true : false;
        $expense->update($validated);

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Expense has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Expense has been deleted successfully.');
    }

    public function toggle(Expense $expense)
    {
        $toggle = ($expense->is_active) ? 'blocked' : 'activated';
        $expense->is_active = ! $expense->is_active;
        $expense->save();

        return redirect()->back()
            ->with('success', "Status of Expense: {$expense->item} has been {$toggle} successfully.");
    }
}
