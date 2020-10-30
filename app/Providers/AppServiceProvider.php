<?php

namespace App\Providers;

use View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Repositories\AuthorRepository;
use App\Repositories\PublisherRepository;
use App\Repositories\UserRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\BookRepository;
use App\Repositories\ReviewRepository;
use App\Repositories\BorrowRepository;
use App\Repositories\LikeRepository;
use App\Repositories\RepositoryInterface\UserRepositoryInterface;
use App\Repositories\RepositoryInterface\CategoryRepositoryInterface;
use App\Repositories\RepositoryInterface\PublisherRepositoryInterface;
use App\Repositories\RepositoryInterface\BookRepositoryInterface;
use App\Repositories\RepositoryInterface\BorrowRepositoryInterface;
use App\Repositories\RepositoryInterface\BaseRepositoryInterface;
use App\Repositories\RepositoryInterface\ReviewRepositoryInterface;
use App\Repositories\RepositoryInterface\LikeRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::defaultView('vendor/pagination/bootstrap-4');
        Paginator::defaultSimpleView('vendor/pagination/simple-bootstrap-4');
    }

    /**
     * Register any application services.
     *
     * @return voi
     */
    public function register()
    {
        $this->app->bind(BaseRepositoryInterface::class, AuthorRepository::class);
        $this->app->bind(PublisherRepositoryInterface::class, PublisherRepository::class);
        $this ->app->bind(ReviewRepositoryInterface::class, ReviewRepository::class);

        $this->app->singleton(
            UserRepositoryInterface::class,
            UserRepository::class
        );

        $this->app->singleton(
            CategoryRepositoryInterface::class,
            CategoryRepository::class
        );

        $this->app->singleton(
            BookRepositoryInterface::class,
            BookRepository::class
        );

        $this->app->singleton(
            BorrowRepositoryInterface::class,
            BorrowRepository::class
        );

        $this->app->singleton(
            LikeRepositoryInterface::class,
            LikeRepository::class
        );

    }
}
