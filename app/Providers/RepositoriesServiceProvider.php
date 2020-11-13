<?php

namespace App\Providers;

use App\Models\User;
use App\Repositories\AttachmentRepository;
use App\Repositories\EmailRepository;
use App\Interfaces\EmailRepositoryInterface;
use App\Repositories\RecipientRepository;
use App\Interfaces\RecipientRepositoryInterface;
use App\Repositories\UserRepository;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
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
        $this->app->bind(UserRepositoryInterface::class,UserRepository::class);
//        $this->app->bind(EmailRepositoryInterface::class,EmailRepository::class);

        $this->app->bind(EmailRepositoryInterface::class, EmailRepository::class);

        $this->app->bind(RecipientRepositoryInterface::class, RecipientRepository::class);

        $this->app->bind(AttachmentRepositoryInterface::class, AttachmentRepository::class);
    }
}
