<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with(['media', 'farms'])->get();
        $roles = Role::all();
        return view(
            'admin.users.index',
            compact('users', 'roles')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // No implementation is required
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20|unique:users',
            'role' => 'required|string|exists:roles,name',
            'file' => 'nullable|mimes:jpeg,jpg,png|max:2000',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
        ]);

        $user->assignRole($validated['role']);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $user->addMedia($file);
        }

        return redirect()
            ->route('clients.index')
            ->with('success', 'User is added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($userId)
    {
        $user = User::with('media')->findOrFail($userId);
        $media = $user->media->first();
        if($media) $user->deleteMedia($media->id);
        $user->delete();
        return redirect()
            ->route('clients.index')
            ->with('success', 'User is deleted successfully.');
    }
}
