<?php

namespace App\Repositories\Interfaces;

interface MediaRepositoryInterface
{
    public function getAllFiles();
    public function getFileById($id);
    public function getFileBySlug($slug);
    public function createFile(array $fileDetails);
    public function addSlugToFile($fileId, string $slug);
    public function deleteFile($id);
    public function updateFile($id, array $newDetails);
}
