<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $settings = Setting::all()->groupBy('group');

            return response()->json([
                'success' => true,
                'data' => $settings,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve settings',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display settings by group.
     */
    public function byGroup($group)
    {
        try {
            $settings = Setting::where('group', $group)->get();

            if ($settings->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No settings found for group: '.$group,
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $settings,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve settings for group: '.$group,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get a specific setting by group and key.
     */
    public function show($group, $key)
    {
        try {
            $setting = Setting::where('group', $group)
                ->where('key', $key)
                ->first();

            if (! $setting) {
                return response()->json([
                    'success' => false,
                    'message' => "Setting not found: {$group}.{$key}",
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $setting,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve setting',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'group' => 'required|string|max:255',
            'key' => 'required|string|max:255',
            'value' => 'required',
            'type' => 'nullable|string|max:255',
            'is_encrypted' => 'boolean',
            'description' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Check if setting already exists
            $existingSetting = Setting::where('group', $request->group)
                ->where('key', $request->key)
                ->first();

            if ($existingSetting) {
                return response()->json([
                    'success' => false,
                    'message' => "Setting already exists: {$request->group}.{$request->key}",
                ], 409);
            }

            $setting = Setting::create([
                'group' => $request->group,
                'key' => $request->key,
                'value' => json_encode($request->value),
                'type' => $request->type ?? 'string',
                'is_encrypted' => $request->is_encrypted ?? false,
                'description' => $request->description,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Setting created successfully',
                'data' => $setting,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create setting',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $group, $key)
    {
        $validator = Validator::make($request->all(), [
            'value' => 'required',
            'type' => 'nullable|string|max:255',
            'is_encrypted' => 'boolean',
            'description' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $setting = Setting::where('group', $group)
                ->where('key', $key)
                ->first();

            if (! $setting) {
                return response()->json([
                    'success' => false,
                    'message' => "Setting not found: {$group}.{$key}",
                ], 404);
            }

            $setting->update([
                'value' => json_encode($request->value),
                'type' => $request->type ?? $setting->type,
                'is_encrypted' => $request->has('is_encrypted') ? $request->is_encrypted : $setting->is_encrypted,
                'description' => $request->description ?? $setting->description,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Setting updated successfully',
                'data' => $setting->fresh(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update setting',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update multiple settings at once.
     */
    public function bulkUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'settings' => 'required|array',
            'settings.*.group' => 'required|string|max:255',
            'settings.*.key' => 'required|string|max:255',
            'settings.*.value' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();

            $updatedSettings = [];

            foreach ($request->settings as $settingData) {
                $setting = Setting::where('group', $settingData['group'])
                    ->where('key', $settingData['key'])
                    ->first();

                if ($setting) {
                    $setting->update([
                        'value' => json_encode($settingData['value']),
                    ]);
                    $updatedSettings[] = $setting->fresh();
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Settings updated successfully',
                'data' => $updatedSettings,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to update settings',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($group, $key)
    {
        try {
            $setting = Setting::where('group', $group)
                ->where('key', $key)
                ->first();

            if (! $setting) {
                return response()->json([
                    'success' => false,
                    'message' => "Setting not found: {$group}.{$key}",
                ], 404);
            }

            $setting->delete();

            return response()->json([
                'success' => true,
                'message' => 'Setting deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete setting',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get company settings.
     */
    public function companySettings()
    {
        return $this->byGroup('company');
    }

    /**
     * Get social media settings.
     */
    public function socialSettings()
    {
        return $this->byGroup('social');
    }

    /**
     * Get contact settings.
     */
    public function contactSettings()
    {
        return $this->byGroup('contact');
    }

    /**
     * Get public settings (non-sensitive data).
     */
    public function publicSettings()
    {
        try {
            $publicGroups = ['company', 'social', 'contact'];
            $settings = Setting::whereIn('group', $publicGroups)
                ->where('is_encrypted', false)
                ->get()
                ->groupBy('group');

            return response()->json([
                'success' => true,
                'data' => $settings,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve public settings',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function personal()
    {
        return view('admin.coming-soon');
    }

    public function general()
    {
        return view('admin.coming-soon');
    }
}
