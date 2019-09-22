<?php

namespace App\Providers;

use App\Repository\ConversationRepository;
use App\Repository\Interfaces\ConversationRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(ConversationRepositoryInterface::class, ConversationRepository::class);
    }
}
