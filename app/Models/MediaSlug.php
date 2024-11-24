<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MediaSlug extends Model
{
    protected $fillable = [
        'media_file_id',
        'slug'
    ];

    public function mediaFile(): BelongsTo
    {
        return $this->belongsTo(MediaFile::class);
    }
}
