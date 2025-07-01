<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Model;

class MediaService
{
    /**
     * @throws \Exception
     */
    public function storeImage(Model $model, UploadedFile $file, string $folder = 'media')
    {
        if (!method_exists($model, 'addMedia')) {
            throw new \Exception("Model must use HasMedia trait");
        }

        return $model->addMedia($file, $folder);
    }

    public function deleteMedia(Model $model, int $mediaId)
    {
        return $model->deleteMedia($mediaId);
    }

    public function reorderMedia(Model $model, array $orderedIds)
    {
        return $model->reorderMedia($orderedIds);
    }
}
