<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Shortcut;
use Illuminate\Http\Request;

class ShortcutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shortcuts = Shortcut::all();

        return view('admin.shortcuts.index', compact('shortcuts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.shortcuts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
        ]);
        Shortcut::create($data);

        return redirect()
            ->route('shortcuts.index')
            ->with('success', 'Shortcut created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Shortcut $shortcut)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shortcut $shortcut)
    {
        return view('admin.shortcuts.create', compact('shortcut'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Shortcut $shortcut)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
        ]);
        $shortcut->update($data);

        return redirect()
            ->route('shortcuts.index')
            ->with('success', 'Shortcut updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shortcut $shortcut)
    {
        $shortcut->delete();

        return redirect()
            ->route('shortcuts.index')
            ->with('success', 'Shortcut deleted successfully!');
    }

    /**
     * Get all admin shortcuts
     */
    public function getAdminShortcuts()
    {
        $adminShortcuts = Shortcut::admin()->get();

        return response()->json([
            'admin_shortcuts' => $adminShortcuts,
        ]);
    }

    /**
     * Get all user shortcuts
     */
    public function getUserShortcuts()
    {
        $userShortcuts = Shortcut::userGroup()->get();

        return response()->json([
            'user_shortcuts' => $userShortcuts,
        ]);
    }

    /**
     * Get default admin shortcuts
     */
    public function getDefaultAdminShortcuts()
    {
        $defaultAdminShortcuts = Shortcut::admin()->default()->get();

        return response()->json([
            'default_admin_shortcuts' => $defaultAdminShortcuts,
        ]);
    }

    /**
     * Get default user shortcuts
     */
    public function getDefaultUserShortcuts()
    {
        $defaultUserShortcuts = Shortcut::userGroup()->default()->get();

        return response()->json([
            'default_user_shortcuts' => $defaultUserShortcuts,
        ]);
    }

    /**
     * Get shortcuts by group
     */
    public function getShortcutsByGroup($group)
    {
        $shortcuts = Shortcut::getByGroup($group);

        return response()->json([
            'shortcuts' => $shortcuts,
        ]);
    }

    /**
     * Get user's personalized shortcuts
     */
    public function getUserPersonalizedShortcuts()
    {
        $user = auth()->user();

        // If user has custom shortcuts, return them
        if ($user->shortcuts()->exists()) {
            $shortcuts = $user->shortcuts()->get();
        } else {
            // Otherwise return default shortcuts based on user role
            $group = $user->is_admin ? 'admin' : 'user';
            $shortcuts = Shortcut::getDefaultByGroup($group);
        }

        return response()->json([
            'shortcuts' => $shortcuts,
        ]);
    }
}
