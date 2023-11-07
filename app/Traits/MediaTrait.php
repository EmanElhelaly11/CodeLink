<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait MediaTrait
{
    public static function upload($image, string $dir): string
    {
        $uniqueFileName = uniqid() . '.' . $image->extension();
        $photoPath = $image->storeAs($dir, $uniqueFileName, 'public');
        return $photoPath;
    }

    public static function uploadVideo($video, string $dir): string
    {
        $uniqueFileName = uniqid() . '.' . $video->extension();
        $videoPath = $video->storeAs($dir, $uniqueFileName, 'public');
        return $videoPath;
    }

    public static function delete(string $fullPublicPath): bool
    {
        $updatedPath = str_replace('storage/', '', $fullPublicPath);

        if (Storage::disk('public')->exists($updatedPath)) {
            Storage::disk('public')->delete($updatedPath);
            return true;
        }

        return false;
    }
}
