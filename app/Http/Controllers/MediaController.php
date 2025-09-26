<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use App\Models\Vehicule;
use App\Models\Client;
use App\Models\Agence;

class MediaController extends Controller
{
    /**
     * Serve private images with signed URLs
     */
    public function show(Request $request, $type, $id)
    {
        // Validate type
        $allowedTypes = ['vehicule', 'client', 'agence'];
        if (!in_array($type, $allowedTypes)) {
            abort(404);
        }

        // Get the model
        $model = $this->getModel($type, $id);
        if (!$model) {
            abort(404);
        }

        // Check if user has access to this resource
        if (!$this->hasAccess($model)) {
            abort(403, 'Access denied');
        }

        // Get image path
        $imagePath = $this->getImagePath($model, $type);
        if (!$imagePath || !Storage::disk('private_images')->exists($imagePath)) {
            abort(404);
        }

        // Serve the file
        return $this->serveFile($imagePath);
    }

    /**
     * Get model instance
     */
    private function getModel($type, $id)
    {
        switch ($type) {
            case 'vehicule':
                return Vehicule::find($id);
            case 'client':
                return Client::find($id);
            case 'agence':
                return Agence::find($id);
            default:
                return null;
        }
    }

    /**
     * Check if user has access to the resource
     */
    private function hasAccess($model)
    {
        // For authenticated users, check tenant access
        if (auth()->check()) {
            if (isset($model->tenant_id)) {
                $tenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;
                return $model->tenant_id === $tenantId;
            }
        }

        // For public access (landing page), allow access to active vehicles
        if ($model instanceof Vehicule) {
            return $model->is_active && $model->landing_display;
        }

        return false;
    }

    /**
     * Get image path from model
     */
    private function getImagePath($model, $type)
    {
        switch ($type) {
            case 'vehicule':
                return $model->image;
            case 'client':
                return $model->document;
            case 'agence':
                return $model->logo;
            case 'marque':
                return $model->image;
            default:
                return null;
        }
    }

    /**
     * Serve file with proper headers
     */
    private function serveFile($imagePath)
    {
        $stream = Storage::disk('private_images')->readStream($imagePath);
        
        if (!$stream) {
            abort(404);
        }

        $mimeType = Storage::disk('private_images')->mimeType($imagePath) ?? 'application/octet-stream';
        $filename = basename($imagePath);

        return response()->stream(function() use ($stream) {
            fpassthru($stream);
        }, 200, [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'private, max-age=3600',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }

    /**
     * Generate signed URL for private images
     */
    public static function getSignedUrl($type, $id, $expiration = 60)
    {
        return URL::temporarySignedRoute(
            'media.show',
            now()->addMinutes($expiration),
            ['type' => $type, 'id' => $id]
        );
    }
}
