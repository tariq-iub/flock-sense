<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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
                'farms' => fn ($query) => $query->withCount('sheds'),
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
        // For multipart form data, try different approaches to get the data
        $isMultipart = str_contains($request->header('Content-Type', ''), 'multipart/form-data');

        try {
            $validated = $request->validate([
                'name' => ['nullable', 'string', 'max:255'],
                'email' => ['nullable', 'email', Rule::unique('users')->ignore($user->id)],
                'phone' => ['nullable', Rule::unique('users')->ignore($user->id)],
                'password' => ['nullable', 'min:6'],
                'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        }

        // If validation returned empty, try to extract data manually
        if (empty($validated)) {
            $manualData = [];
            $requestData = $request->all();

            // For multipart form data, try different methods
            if ($isMultipart) {
                // Try input() method
                $inputData = $request->input();

                if (! empty($inputData)) {
                    $requestData = $inputData;
                } else {
                    // Manual parsing of multipart form data
                    $rawContent = $request->getContent();

                    // Parse multipart form data manually
                    $boundary = null;
                    if (preg_match('/boundary=([^;]+)/', $request->header('Content-Type'), $matches)) {
                        $boundary = $matches[1];
                    }

                    if ($boundary) {
                        $parts = explode("--$boundary", $rawContent);
                        foreach ($parts as $part) {
                            if (preg_match('/Content-Disposition: form-data; name="([^"]+)"/', $part, $nameMatches)) {
                                $fieldName = $nameMatches[1];

                                // Skip file fields - they should be handled by Laravel's file handling
                                if (str_contains($part, 'filename=')) {
                                    Log::info("Skipping file field: $fieldName");
                                    continue;
                                }

                                // Extract the value (everything after the double newline)
                                $valueStart = strpos($part, "\r\n\r\n");
                                if ($valueStart !== false) {
                                    $value = substr($part, $valueStart + 4);
                                    $value = trim($value);
                                    if (! empty($value)) {
                                        $manualData[$fieldName] = $value;
                                    }
                                }
                            }
                        }
                    }
                }
            }

            // Extract only the fields we want to update
            if (isset($requestData['name'])) {
                $manualData['name'] = $requestData['name'];
            }
            if (isset($requestData['email'])) {
                $manualData['email'] = $requestData['email'];
            }
            if (isset($requestData['phone'])) {
                $manualData['phone'] = $requestData['phone'];
            }
            if (isset($requestData['password'])) {
                $manualData['password'] = $requestData['password'];
            }

            if (! empty($manualData)) {
                $validated = $manualData;
            }
        }

        // Check if any data was provided
        if (empty($validated)) {
            return response()->json([
                'message' => 'No data provided for update.',
                'user' => UserResource::make($user->load('media')),
            ], 400);
        }

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        // Handle image upload - try multiple methods for multipart form data
        $avatarFile = null;
        if ($request->hasFile('avatar')) {
            $avatarFile = $request->file('avatar');
        } elseif ($isMultipart && $request->file('avatar')) {
            $avatarFile = $request->file('avatar');
        }

        // For multipart form data, also check if we manually parsed a file
        if ($isMultipart && ! $avatarFile && isset($manualData['avatar'])) {
            // Try to get the file from the request files
            $allFiles = $request->allFiles();

            if (isset($allFiles['avatar'])) {
                $avatarFile = $allFiles['avatar'];
            } else {
                // Try to get it directly
                $avatarFile = $request->file('avatar');
            }
        }

        // If still no file, try manual extraction from multipart data
        if ($isMultipart && ! $avatarFile) {
            $rawContent = $request->getContent();
            $boundary = null;

            if (preg_match('/boundary=([^;]+)/', $request->header('Content-Type'), $matches)) {
                $boundary = $matches[1];
            }

            if ($boundary) {
                $parts = explode("--$boundary", $rawContent);
                foreach ($parts as $part) {
                    if (preg_match('/Content-Disposition: form-data; name="avatar"; filename="([^"]+)"/', $part, $matches)) {
                        $filename = $matches[1];
                        $fileStart = strpos($part, "\r\n\r\n");

                        if ($fileStart !== false) {
                            $fileContent = substr($part, $fileStart + 4);
                            $fileContent = trim($fileContent);

                            if (! empty($fileContent)) {
                                // Create a temporary file
                                $tempPath = tempnam(sys_get_temp_dir(), 'avatar_');
                                file_put_contents($tempPath, $fileContent);

                                // Create a file object
                                $avatarFile = new \Illuminate\Http\UploadedFile(
                                    $tempPath,
                                    $filename,
                                    'image/jpeg',
                                    null,
                                    true
                                );
                            }
                        }
                        break;
                    }
                }
            }
        }

        //        if ($avatarFile) {
        //            // Delete existing avatar if any
        //            $existingAvatar = $user->media()->first();
        //            if ($existingAvatar) {
        //                $user->deleteMedia($existingAvatar->id);
        //            }
        //
        //            $user->addMedia($avatarFile, 'avatars');
        //        }

        if ($avatarFile) {
            $filename = time().'_'.$avatarFile->getClientOriginalName();
            $path = $avatarFile->storeAs('avatars', $filename, 'public'); // Save to public disk
            $existingAvatar = $user->media()->first();

            if ($existingAvatar) {
                Storage::disk('public')->delete($existingAvatar->file_path); // Delete old file
                $existingAvatar->delete();
            }

            $user->media()->create([
                'model_type' => get_class($user),
                'model_id' => $user->id,
                'file_name' => $filename,
                'file_path' => $path, // e.g., avatars/1754822530_media.jpg
                'size' => $avatarFile->getSize(),
                'order_column' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Remove avatar from validated data since we handle it separately
        unset($validated['avatar']);

        $originalValues = $user->only(array_keys($validated));
        $updated = $user->update($validated);
        $user->refresh();
        $newValues = $user->only(array_keys($validated));

        return response()->json([
            'message' => 'User updated successfully.',
            'user' => UserResource::make($user->load('media')),
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
