<?php

namespace Laravel\Spark\Providers;

use Laravel\Spark\Spark;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class SparkServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->defineRoutes();

        $this->defineResources();
    }

    /**
     * Define the Spark routes.
     *
     * @return void
     */
    protected function defineRoutes()
    {
        // If the routes have not been cached, we will include them in a route group
        // so that all of the routes will be conveniently registered to the given
        // controller namespace. After that we will load the Spark routes file.
        if (! $this->app->routesAreCached()) {
            Route::group([
                'namespace' => 'Laravel\Spark\Http\Controllers'],
                function ($router) {
                    require __DIR__.'/../Http/routes.php';
                }
            );
        }
    }

    /**
     * Define the resources for the package.
     *
     * @return void
     */
    protected function defineResources()
    {
        $this->loadViewsFrom(SPARK_PATH.'/resources/views', 'spark');

        $this->loadTranslationsFrom(SPARK_PATH.'/resources/lang', 'spark');

        if ($this->app->runningInConsole()) {
            $this->defineViewPublishing();

            $this->defineAssetPublishing();

            $this->defineFullPublishing();
        }
    }

    /**
     * Define the view publishing configuration.
     *
     * @return void
     */
    public function defineViewPublishing()
    {
        $this->publishes([
            SPARK_PATH.'/resources/views' => resource_path('views/vendor/spark'),
        ], 'spark-views');
    }

    /**
     * Define the asset publishing configuration.
     *
     * @return void
     */
    public function defineAssetPublishing()
    {
        $this->publishes([
            SPARK_PATH.'/resources/assets/js' => resource_path('assets/js/spark'),
        ], 'spark-js');

        $this->publishes([
            SPARK_PATH.'/resources/assets/less' => resource_path('assets/less/spark'),
        ], 'spark-less');
    }

    /**
     * Define the "full" publishing configuration.
     *
     * @return void
     */
    public function defineFullPublishing()
    {
        $this->publishes([
            SPARK_PATH.'/resources/views' => resource_path('views/vendor/spark'),
            SPARK_PATH.'/resources/assets/js' => resource_path('assets/js/spark'),
            SPARK_PATH.'/resources/assets/less' => resource_path('assets/less/spark'),
        ], 'spark-full');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        if (! defined('SPARK_PATH')) {
            define('SPARK_PATH', realpath(__DIR__.'/../../'));
        }

        if (! class_exists('Spark')) {
            class_alias('Laravel\Spark\Spark', 'Spark');
        }

        if ($this->app->runningInConsole()) {
            $this->commands([]);
        }

        $this->registerServices();
    }

    /**
     * Register the Spark services.
     *
     * @return void
     */
    protected function registerServices()
    {
        $this->registerInterventionService();

        $services = [
            'Contracts\Http\Requests\Auth\RegisterRequest' => 'Http\Requests\Auth\RegisterRequest',
            'Contracts\Repositories\UserRepository' => 'Repositories\UserRepository',
            'Contracts\InitialFrontendState' => 'InitialFrontendState',
            'Contracts\Interactions\Support\SendSupportEmail' => 'Interactions\Support\SendSupportEmail',
            'Contracts\Interactions\Auth\CreateUser' => 'Interactions\Auth\CreateUser',
            'Contracts\Interactions\Auth\Register' => 'Interactions\Auth\Register',
            'Contracts\Interactions\Settings\Profile\UpdateProfilePhoto' => 'Interactions\Settings\Profile\UpdateProfilePhoto',
            'Contracts\Interactions\Settings\Profile\UpdateContactInformation' => 'Interactions\Settings\Profile\UpdateContactInformation',
        ];

        foreach ($services as $key => $value) {
            $this->app->singleton('Laravel\Spark\\'.$key, 'Laravel\Spark\\'.$value);
        }
    }

    /**
     * Register the Intervention image manager binding.
     *
     * @return void
     */
    protected function registerInterventionService()
    {
        $this->app->bind(ImageManager::class, function () {
            return new ImageManager(['driver' => 'gd']);
        });
    }
}
