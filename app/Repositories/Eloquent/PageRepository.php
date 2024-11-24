<?php

namespace App\Repositories\Eloquent;

use App\Models\Page;
use App\Repositories\Interfaces\PageRepositoryInterface;

class PageRepository implements PageRepositoryInterface
{
    protected $model;

    public function __construct(Page $model)
    {
        $this->model = $model;
    }

    public function getAllPages()
    {
        return $this->model->all();
    }

    public function getPageById($pageId)
    {
        return $this->model->findOrFail($pageId);
    }

    public function deletePage($pageId)
    {
        return $this->model->destroy($pageId);
    }

    public function createPage(array $pageDetails)
    {
        return $this->model->create($pageDetails);
    }

    public function updatePage($pageId, array $newDetails)
    {
        return $this->model->whereId($pageId)->update($newDetails);
    }
}
