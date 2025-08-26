<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class FarmScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $user = Auth::user();

        // If no user is authenticated, we return no results for security
        if (! $user) {
            $builder->where('id', null);

            return;
        }

        // Admins can see all farms
        if ($user->hasRole('admin')) {
            return;
        }

        // Owners can only see their own farms
        if ($user->hasRole('owner')) {
            $builder->where('owner_id', $user->id);

            return;
        }

        // Managers can only see the farms they are assigned to via the pivot table
        if ($user->hasRole('manager')) {
            $builder->whereHas('managers', function ($query) use ($user) {
                $query->where('manager_id', $user->id);
            });

            return;
        }

        // If the user has a different role or no role, return no farms
        $builder->where('id', null);
    }
}
