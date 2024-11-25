<?php

namespace App\Services;

use App\Repositories\Interfaces\PageRepositoryInterface;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class PageService
{
    protected $pageRepository;

    public function __construct(PageRepositoryInterface $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }

    public function getAllPages()
    {
        return $this->pageRepository->getAllPages();
    }

    public function createPage(array $data)
    {
        $validator = Validator::make($data, [
            'title' => 'required|string|max:255',
            'body' => 'required|string'
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        }

        return $this->pageRepository->createPage($data);
    }

    public function getPageById($pageId)
    {
        return $this->pageRepository->getPageById($pageId);
    }

    public function updatePage($pageId, array $data)
    {
        $validator = Validator::make($data, [
            'title' => 'sometimes|required|string|max:255',
            'body' => 'sometimes|required|string'
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        }

        return $this->pageRepository->updatePage($pageId, $data);
    }

    public function deletePage($pageId)
    {
        return $this->pageRepository->deletePage($pageId);
    }

    public function attachMedia($pageId, $mediaId)
    {
        $page = $this->pageRepository->getPageById($pageId);
        $page->mediaFiles()->attach($mediaId);
        return $page->fresh('mediaFiles');
    }

    public function detachMedia($pageId, $mediaId)
    {
        $page = $this->pageRepository->getPageById($pageId);
        $page->mediaFiles()->detach($mediaId);
        return $page->fresh('mediaFiles');
    }

    public function getPageWithMedia($pageId)
    {
        return $this->pageRepository->getPageById($pageId)->load('mediaFiles');
    }
}
