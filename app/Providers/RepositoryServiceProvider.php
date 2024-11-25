<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\PageRepositoryInterface;
use App\Repositories\Eloquent\PageRepository;
use App\Repositories\Interfaces\MediaRepositoryInterface;
use App\Repositories\Eloquent\MediaRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->app->bind(PageRepositoryInterface::class, PageRepository::class);
        $this->app->bind(MediaRepositoryInterface::class, MediaRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
