<?php

namespace App\Traits;

use App\Models\Media;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait HasMedia
{
    public function media() : MorphMany
    {
        return $this->morphMany(Media::class, 'model');
    }

    public function addMedia($file, $folder = 'media')
    {
        $name = time() . '_media';
        $filename = $name . '.' . $file->getClientOriginalExtension();

        $destinationPath = public_path("{$folder}");
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0775, true);
        }

        $size = $file->getSize();
        $file->move($destinationPath, $filename);
        $path = "{$folder}/{$filename}";

        return $this->media()->create([
            'file_name' => $filename,
            'file_path' => $path,
            'size' => $size,
        ]);
    }

    public function deleteMedia($mediaId): ?bool
    {
        $media = $this->media()->findOrFail($mediaId);
        if (File::exists($media->file_path)) {
            File::delete($media->file_path);
        }
        return $media->delete();
    }

    public function reorderMedia(array $orderedIds): void
    {
        foreach ($orderedIds as $index => $id) {
            $this->media()->where('id', $id)->update(['order_column' => $index]);
        }
    }
}
