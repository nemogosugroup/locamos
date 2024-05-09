<?php

namespace App\Providers;

use App\Repositories\Post\PostRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\User\UserRepository;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Backend\BaseCategoryRepository;
use App\Repositories\Backend\BasePostRepository;
use App\Repositories\Backend\Interfaces\BaseCategoryRepositoryInterface;
use App\Repositories\Backend\Interfaces\BasePostRepositoryInterface;
use App\Repositories\Interfaces\Backend\PostRepositoryInterface;
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(BaseCategoryRepositoryInterface::class, BaseCategoryRepository::class);
        $this->app->bind(BasePostRepositoryInterface::class, BasePostRepository::class);
        $this->app->bind(PostRepositoryInterface::class, PostRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
