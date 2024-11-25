<?php

namespace App\Repositories\Eloquent;

use App\Models\MediaFile;
use App\Models\MediaSlug;
use App\Repositories\Interfaces\MediaRepositoryInterface;

class MediaRepository implements MediaRepositoryInterface
{
    protected $mediaFile;
    protected $mediaSlug;

    public function __construct(MediaFile $mediaFile, MediaSlug $mediaSlug)
    {
        $this->mediaFile = $mediaFile;
        $this->mediaSlug = $mediaSlug;
    }

    public function getAllFiles()
    {
        return $this->mediaFile->with('slugs')->get();
    }

    public function getFileById($id)
    {
        return $this->mediaFile->with('slugs')->findOrFail($id);
    }

    public function getFileBySlug($slug)
    {
        return $this->mediaFile->whereHas('slugs', function($query) use ($slug) {
            $query->where('slug', $slug);
        })->with('slugs')->firstOrFail();
    }

    public function createFile(array $fileDetails)
    {
        $file = $this->mediaFile->create($fileDetails);

        if (isset($fileDetails['slug'])) {
            $this->addSlugToFile($file->id, $fileDetails['slug']);
        }

        return $file->load('slugs');
    }

    public function addSlugToFile($fileId, string $slug)
    {
        return $this->mediaSlug->create([
            'media_file_id' => $fileId,
            'slug' => $slug
        ]);
    }

    public function deleteFile($id)
    {
        $file = $this->mediaFile->findOrFail($id);
        return $file->delete();
    }

    public function updateFile($id, array $newDetails)
    {
        $file = $this->mediaFile->findOrFail($id);
        $file->update($newDetails);
        return $file->fresh('slugs');
    }
}
