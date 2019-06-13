<?php

namespace App\Providers;

use App\Configuration\Spark;
use Illuminate\Support\ServiceProvider;
use Intervention\Image\ImageManager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The address where customer support e-mails should be sent.
     *
     * @var string
     */
    protected $sendSupportEmailsTo = "veejspeej@gmail.com";

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Spark::sendSupportEmailsTo($this->sendSupportEmailsTo);

    }
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
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
            'Contracts\Interactions\Support\SendSupportEmail' => 'Interactions\Support\SendSupportEmail',
            'Contracts\Interactions\Auth\Register' => 'Interactions\Auth\Register',
            'Contracts\Interactions\Settings\Profile\UpdateProfilePhoto' => 'Interactions\Settings\Profile\UpdateProfilePhoto',
            'Contracts\Interactions\Settings\Profile\UpdateContactInformation' => 'Interactions\Settings\Profile\UpdateContactInformation',
        ];

        foreach ($services as $key => $value) {
            $this->app->singleton('App\\'.$key, 'App\\'.$value);
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
