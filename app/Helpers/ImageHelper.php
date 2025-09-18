<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\MediaController;

class ImageHelper
{
    /**
     * Store uploaded file to private storage
     */
    public static function storePrivateImage($file, $folder = '')
    {
        if (!$file || !$file->isValid()) {
            return null;
        }

        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $folder ? $folder . '/' . $filename : $filename;
        
        $file->storeAs('', $path, 'private_images');
        
        return $path;
    }

    /**
     * Get signed URL for private image
     */
    public static function getPrivateImageUrl($type, $id, $expiration = 60)
    {
        return MediaController::getSignedUrl($type, $id, $expiration);
    }

    /**
     * Delete private image
     */
    public static function deletePrivateImage($imagePath)
    {
        if ($imagePath && Storage::disk('private_images')->exists($imagePath)) {
            return Storage::disk('private_images')->delete($imagePath);
        }
        return false;
    }

    /**
     * Get image URL for different model types
     */
    public static function getModelImageUrl($model, $type = null)
    {
        if (!$model) return null;

        $imagePath = null;
        $modelType = $type ?: class_basename($model);

        switch (strtolower($modelType)) {
            case 'vehicule':
                $imagePath = $model->image;
                break;
            case 'client':
                $imagePath = $model->document;
                break;
            case 'agence':
                $imagePath = $model->logo;
                break;
            case 'marque':
                $imagePath = $model->image;
                break;
        }

        if (!$imagePath) return null;

        return self::getPrivateImageUrl(strtolower($modelType), $model->id);
    }

    /**
     * Check if image exists in private storage
     */
    public static function imageExists($imagePath)
    {
        return $imagePath && Storage::disk('private_images')->exists($imagePath);
    }
}
