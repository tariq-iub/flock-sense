<?php

namespace App\Http\Controllers\Web;

use App\Models\Province;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

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
            'password_reset_required' => true,
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
    public function show($userId)
    {
        $user = User::with([
            'farms.sheds.flocks',
            'settings',
            'farms' => fn ($query) => $query->withCount('sheds'),
        ])->withCount('farms')
            ->findOrFail($userId);

        if ($user->settings == null) {
            $user->settings = $user->settings()->create([
                'security_level' => 'medium',
                'backup_frequency' => 'daily',
                'language' => 'en',
                'timezone' => 'UTC',
                'notifications_email' => true,
                'notifications_sms' => false,
            ]);
        }

        $provinces = Province::select('id', 'name')
            ->orderBy('name')
            ->get();

        return view(
            'admin.users.show',
            compact('user', 'provinces')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::with(['media', 'settings', 'roles'])
            ->find($id);

        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::with('media')->find($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'role' => 'required|string|exists:roles,name',
            'file' => 'nullable|mimes:jpeg,jpg,png|max:2000',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
        ]);

        $user->syncRoles($validated['role']);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            if ($user->media != null && $user->media->first()) {
                $user->deleteMedia($user->media->first()->id);
            }
            $user->addMedia($file);
        }

        return redirect()
            ->route('clients.index')
            ->with('success', 'User has been updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($userId)
    {
        $user = User::with('media')->findOrFail($userId);
        $media = $user->media->first();
        if ($media) {
            $user->deleteMedia($media->id);
        }
        $user->delete();

        return redirect()
            ->route('clients.index')
            ->with('success', 'User is deleted successfully.');
    }

    public function updatePassword(Request $request, User $user)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if (! Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
            'password_reset_required' => 1,
        ]);

        return back()
            ->with('success', 'Password has been updated successfully.');
    }
}
