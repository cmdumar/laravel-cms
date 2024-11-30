<?php

namespace App\Support;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class CustomPathGenerator implements PathGenerator
{
    public function getPath(Media $media): string
    {
        return 'media/' . $media->file_name;
    }

    public function getPathForConversions(Media $media): string
    {
        return 'media/conversions/' . $media->file_name;
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return 'media/responsive/' . $media->file_name;
    }
}
