<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Farm;
use App\Models\Shed;
use App\Models\Flock;
use App\Models\Device;
use App\Models\Pricing;

class PlanLimitPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Check if user can create a new farm.
     */
    public function createFarm(User $user)
    {
        $pricing = $user->pricing; // Or $user->farm->pricing, adapt as needed
        $currentFarmCount = $user->farms()->count();

        return $currentFarmCount < $pricing->max_farms;
    }

    /**
     * Check if user can create a new shed.
     */
    public function createShed(User $user, Farm $farm)
    {
        $pricing = $user->pricing;
        $currentShedCount = $farm->sheds()->count();

        return $currentShedCount < $pricing->max_sheds;
    }

    /**
     * Check if user can create a new flock.
     */
    public function createFlock(User $user, Farm $farm)
    {
        $pricing = $user->pricing;
        $currentFlockCount = $farm->flocks()->count();

        return $currentFlockCount < $pricing->max_flocks;
    }

    /**
     * Check if user can add a new device.
     */
    public function createDevice(User $user, Farm $farm)
    {
        $pricing = $user->pricing;
        $currentDeviceCount = $farm->devices()->count();

        return $currentDeviceCount < $pricing->max_devices;
    }

    /**
     * Check if user can invite/add another user.
     */
    public function inviteUser(User $user)
    {
        $pricing = $user->pricing;
        $currentUserCount = $user->account->users()->count();

        return $currentUserCount < $pricing->max_users;
    }

    /**
     * Check if user has access to a specific feature.
     * Usage: $this->hasFeature($user, 'auto_control')
     */
    public function hasFeature(User $user, $feature)
    {
        $pricing = $user->pricing;
        return $pricing->feature_flags[$feature] ?? false;
    }
}
