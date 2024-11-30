<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaFile extends Model implements HasMedia
{
    use SoftDeletes;
    use InteractsWithMedia;

    protected $fillable = [
        'original_name',
        'mime_type',
        'file_path',
        'file_size'
    ];

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('files')
            ->useDisk('samba');
    }

    public function slugs(): HasMany
    {
        return $this->hasMany(MediaSlug::class);
    }

    public function pages()
    {
        return $this->belongsToMany(Page::class, 'page_media');
    }
}
