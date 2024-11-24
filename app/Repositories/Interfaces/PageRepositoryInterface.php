<?php

namespace App\Repositories\Interfaces;

interface PageRepositoryInterface
{
    public function getAllPages();
    public function getPageById($pageId);
    public function deletePage($pageId);
    public function createPage(array $pageDetails);
    public function updatePage($pageId, array $newDetails);
}
