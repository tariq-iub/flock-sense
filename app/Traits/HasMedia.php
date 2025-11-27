<?php

namespace App\Traits;

use App\Models\Media;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Storage;

trait HasMedia
{
    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'model');
    }

    public function addMedia($file)
    {
        $name = time().'_media';
        $filename = $name.'.'.$file->getClientOriginalExtension();
        $size = $file->getSize();

        $file->storeAs('', $filename, 'media');

        return $this->media()->create([
            'file_name' => $filename,
            'file_path' => Storage::disk('media')->url($filename),
            'size' => $size,
        ]);
    }

    public function deleteMedia($mediaId): ?bool
    {
        $media = $this->media()->findOrFail($mediaId);

        // Extract just the filename from the URL path
        $filename = basename($media->file_path);

        if (Storage::disk('media')->exists($filename)) {
            Storage::disk('media')->delete($filename);
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
