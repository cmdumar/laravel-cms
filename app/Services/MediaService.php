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
            // Generate a unique filename
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

            // Store file locally (we'll change this to Samba later)
            $path = $file->store('uploads', 'public');

            if (!$path) {
                throw new \Exception('Failed to store file');
            }

            // Create media file record
            $mediaFile = $this->mediaRepository->createFile([
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'file_path' => $path,
                'file_size' => $file->getSize(),
            ]);

            // Add slug if provided, otherwise use UUID
            $slug = $customSlug ?? Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '-' . Str::random(6));
            $this->mediaRepository->addSlugToFile($mediaFile->id, $slug);

            return $mediaFile->fresh('slugs');
        } catch (\Exception $e) {
            // If anything fails, attempt to remove the file if it was uploaded
            if (isset($path) && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            throw $e;
        }
    }

    public function getFileById($id)
    {
        return $this->mediaRepository->getFileById($id);
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

    public function deleteFile($id)
    {
        $file = $this->mediaRepository->getFileById($id);

        // Delete from local storage
        if (Storage::disk('public')->exists($file->file_path)) {
            Storage::disk('public')->delete($file->file_path);
        }

        return $this->mediaRepository->deleteFile($id);
    }
}
