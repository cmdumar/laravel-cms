<?php

namespace App\Services;

use App\Repositories\Interfaces\MediaRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;

class MediaService
{
    protected $mediaRepository;

    public function __construct(MediaRepositoryInterface $mediaRepository)
    {
        $this->mediaRepository = $mediaRepository;
    }

    public function getAllFiles()
    {
        return $this->mediaRepository->getAllFiles();
    }

    public function uploadFile(UploadedFile $file, ?string $customSlug = null)
    {
        try {
            // Create media file record first
            $mediaFile = $this->mediaRepository->createFile([
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(), // Temporary value, will be updated after Spatie processes it
            ]);

            if ($customSlug) {
                $this->mediaRepository->addSlugToFile($mediaFile->id, $customSlug);
            }

            return $mediaFile->fresh('slugs');
        } catch (\Exception $e) {
            if (isset($path) && Storage::disk('samba')->exists($path)) {
                Storage::disk('samba')->delete($path);
            }
            throw $e;
        }
    }

    public function getFileById($id)
    {
        $file = $this->mediaRepository->getFileById($id);
        return $file->load('media');
    }

    public function deleteFile($id)
    {
        $file = $this->mediaRepository->getFileById($id);
        $file->clearMediaCollection('files');
        return $this->mediaRepository->deleteFile($id);
    }

    public function getFileBySlug($slug)
    {
        return $this->mediaRepository->getFileBySlug($slug);
    }

    public function addSlugToFile($fileId, string $slug)
    {
        if (empty($slug)) {
            throw new InvalidArgumentException('Slug cannot be empty');
        }

        return $this->mediaRepository->addSlugToFile($fileId, $slug);
    }

    // public function deleteFile($id)
    // {
    //     $file = $this->mediaRepository->getFileById($id);

    //     // Delete from local storage
    //     if (Storage::disk('public')->exists($file->file_path)) {
    //         Storage::disk('public')->delete($file->file_path);
    //     }

    //     return $this->mediaRepository->deleteFile($id);
    // }
}
