<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = QueryBuilder::for(User::class)
            ->with('media')
            ->withCount('farms')
            ->allowedFilters(['id', 'name'])
            ->allowedIncludes('farms')
            ->allowedSorts(['id', 'name'])
            ->get();

//        return $users;
        return UserResource::collection($users);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return UserResource::make(
            User::with([
                'farms.sheds.flocks',
                'farms' => fn($query) => $query->withCount('sheds'),
            ])
                ->withCount('farms')
                ->findOrFail($user->id)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
        $validated = $request->validate([
            'name' => ['string', 'max:255'],
            'email' => ['email', Rule::unique('users')->ignore($user->id)],
            'phone' => [Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'min:6'],
            'avatar' => ['nullable', 'string'],
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'message' => 'User updated successfully.',
            'user' => UserResource::make($user),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully.',
        ], 200);
    }
}
