<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MediaFile extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'original_name',
        'mime_type',
        'file_path',
        'file_size'
    ];

    public function slugs(): HasMany
    {
        return $this->hasMany(MediaSlug::class);
    }

    public function pages()
    {
        return $this->belongsToMany(Page::class, 'page_media');
    }
}
