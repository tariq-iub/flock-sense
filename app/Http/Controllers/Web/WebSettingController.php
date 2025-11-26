<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WebSettingController extends Controller
{
    /**
     * Display a listing of the settings by group.
     */
    public function index()
    {
        $settings = Setting::all()->groupBy('group');

        return view(
            'admin.settings.index',
            compact('settings')
        );
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
        $validator = $request->validate([
            'group' => 'required|string|max:255',
            'key' => 'required|string|max:255',
            'value' => 'required',
            'type' => 'nullable|string|max:255',
            'is_encrypted' => 'sometimes',
            'description' => 'nullable|string|max:255',
        ]);

        // Convert checkbox value to boolean
        $validator['is_encrypted'] = $request->has('is_encrypted');

        try {
            // Check if setting already exists
            $existingSetting = Setting::where('group', $request->group)
                ->where('key', $request->key)
                ->first();

            if ($existingSetting) {
                return redirect()
                    ->route('web-settings.index')
                    ->with('error', "Setting already exists: {$request->group}.{$request->key}");
            }

            Setting::create([
                'group' => $validator['group'],
                'key' => $validator['key'],
                'value' => $validator['value'],
                'type' => $validator['type'] ?? 'string',
                'is_encrypted' => $validator['is_encrypted'] ?? false,
                'description' => $validator['description'],
            ]);

            return redirect()
                ->route('web-settings.index')
                ->with('success', "Setting is created successfully with {$request->group}.{$request->key}");

        } catch (\Exception $e) {
            return redirect()
                ->route('web-settings.index')
                ->with('error', "Failed to create setting: $e->getMessage()");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'value' => ['required'],
            'description' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'in:string,number,boolean,json,url'],
        ]);

        $setting = Setting::find($id);

        if (! $setting) {
            return response()->json([
                'success' => false,
                'message' => "Setting not found for id: {$id}",
            ], 404);
        }

        // Decide final type: request overrides existing
        $type = $validated['type'] ?? $setting->type ?? 'string';
        $rawValue = $validated['value'];

        switch ($type) {
            case 'json':
                // If we receive a string, try to decode; if we receive an array, use it directly
                if (is_string($rawValue)) {
                    $decoded = json_decode($rawValue, true);
                    $setting->value = json_last_error() === JSON_ERROR_NONE
                        ? $decoded
                        : $rawValue; // fallback: store raw string
                } else {
                    // Frontend is already sending object → will arrive as array
                    $setting->value = $rawValue; // cast handles JSON encoding
                }
                break;

            case 'boolean':
                $setting->value = filter_var($rawValue, FILTER_VALIDATE_BOOLEAN);
                break;

            case 'number':
                $setting->value = is_numeric($rawValue)
                    ? $rawValue + 0 // normalize to int/float
                    : $rawValue;
                break;

            case 'string':
            default:
                $setting->value = (string) $rawValue;
                break;
        }

        $setting->type = $type;
        $setting->description = $validated['description'] ?? $setting->description;

        $setting->save();

        return response()->json([
            'success' => true,
            'message' => 'Setting updated successfully',
            'data' => $setting->fresh(),
        ]);
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
    public function destroy($id)
    {
        try {
            $setting = Setting::find($id);

            if (! $setting) {
                return redirect()
                    ->route('web-settings.index')
                    ->with('error', "Setting is not found for id: $id");
            }

            $setting->delete();

            return redirect()
                ->route('web-settings.index')
                ->with('success', 'Setting is deleted successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->route('web-settings.index')
                ->with('error', "Failed to delete setting: $e->getMessage()");
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
