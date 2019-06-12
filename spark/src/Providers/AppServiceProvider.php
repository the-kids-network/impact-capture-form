<?php

namespace Laravel\Spark\Providers;

use Laravel\Spark\Spark;
use Braintree_Configuration;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The address where customer support e-mails should be sent.
     *
     * @var string
     */
    protected $sendSupportEmailsTo = null;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Spark::sendSupportEmailsTo($this->sendSupportEmailsTo);

        $this->booted();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
