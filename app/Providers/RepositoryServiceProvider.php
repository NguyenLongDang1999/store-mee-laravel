<?php

namespace App\Providers;

use App\Interfaces\CategoryInterface;
/** Interface */

use App\Repositories\CategoryRepositories;
/** Repositories */

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(CategoryInterface::class, CategoryRepositories::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
