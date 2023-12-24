<?php

namespace App\Providers;

use App\Repositories\ItemRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\CategoryRepository;
use App\Repositories\EmployeeRepository;
use App\Interfaces\ItemRepositoryInterface;
use App\Repositories\ItemsUploadRepository;
use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\EmployeeRepositoryInterface;
use App\Interfaces\ItemsUploadRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    public $bindings = [
        CategoryRepositoryInterface::class => CategoryRepository::class,
        ItemRepositoryInterface::class => ItemRepository::class,
        EmployeeRepositoryInterface::class => EmployeeRepository::class,
        ItemsUploadRepositoryInterface::class => ItemsUploadRepository::class
        
    ];
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(ItemRepositoryInterface::class, ItemRepository::class);
        $this->app->bind(EmployeeRepositoryInterface::class, EmployeeRepository::class);   
        $this->app->bind(ItemsUploadRepositoryInterface::class, ItemsUploadRepository::class);        
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
