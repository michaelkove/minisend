<?php

    namespace App\Providers;

    use App\Interfaces\PhpMailingServiceInterface;
    use App\Interfaces\StoreAttachmentsServiceInterface;
    use App\Services\PhpMailingService;
    use App\Services\StoreAttachmentsService;
    use Illuminate\Support\ServiceProvider;

    class ServiceInterfaceProvider extends ServiceProvider
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
            $this->app->bind(PhpMailingServiceInterface::class,PhpMailingService::class);
            $this->app->bind(StoreAttachmentsServiceInterface::class, StoreAttachmentsService::class);
        }
    }
