<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use App\Http\Resources\UserSettingResource;
use App\Models\UserSetting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserSettingsController extends ApiController
{
    // GET /api/v1/settings
    public function index(Request $request)
    {
        $settings = UserSetting::where('user_id', $request->user()->id)->get();

        return response()->json([
            'status' => 'success',
            'data' => UserSettingResource::collection($settings),
        ]);
    }

    // GET /api/v1/settings/{id}
    public function show(Request $request, $id)
    {
        $setting = UserSetting::where('user_id', $request->user()->id)->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => new UserSettingResource($setting),
        ]);
    }

    // POST /api/v1/settings
    public function store(Request $request)
    {
        $validated = $request->validate([
            'security_level' => ['required', Rule::in(['low', 'medium', 'high'])],
            'backup_frequency' => ['required', Rule::in(['daily', 'weekly', 'monthly'])],
            'language' => ['required', 'string'],
            'timezone' => ['required', 'string'],
            'notifications.email' => ['required', 'boolean'],
            'notifications.sms' => ['required', 'boolean'],
        ]);

        $validated['user_id'] = $request->user()->id;
        $validated['notifications_email'] = $validated['notifications']['email'];
        $validated['notifications_sms'] = $validated['notifications']['sms'];
        unset($validated['notifications']);

        $setting = UserSetting::create($validated);

        return response()->json([
            'status' => 'success',
            'data' => new UserSettingResource($setting),
            'message' => 'User setting created successfully.'
        ], 201);
    }

    // PUT /api/v1/settings/{userId}
    public function update(Request $request, $userId)
    {
        $validated = $request->validate([
            'security_level' => ['sometimes', Rule::in(['low', 'medium', 'high'])],
            'backup_frequency' => ['sometimes', Rule::in(['daily', 'weekly', 'monthly'])],
            'language' => ['sometimes', 'string'],
            'timezone' => ['sometimes', 'string'],
            'notifications.email' => ['sometimes', 'boolean'],
            'notifications.sms' => ['sometimes', 'boolean'],
        ]);

        if (isset($validated['notifications'])) {
            $validated['notifications_email'] = $validated['notifications']['email'] ?? false;
            $validated['notifications_sms'] = $validated['notifications']['sms'] ?? false;
            unset($validated['notifications']);
        }

        $validated['user_id'] = $userId;

        $setting = UserSetting::updateOrCreate(
            ['user_id' => $userId],
            $validated
        );

        return response()->json([
            'status' => 'success',
            'data' => new UserSettingResource($setting),
            'message' => 'User setting updated successfully.'
        ]);
    }

    // DELETE /api/v1/settings/{id}
    public function destroy(Request $request, $id)
    {
        $setting = UserSetting::where('user_id', $request->user()->id)->findOrFail($id);
        $setting->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User setting deleted successfully.',
            'data' => null
        ]);
    }
}
