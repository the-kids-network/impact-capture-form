<?php

namespace App\Providers;

use Laravel\Spark\Spark;
use Laravel\Spark\Providers\AppServiceProvider as ServiceProvider;

class SparkServiceProvider extends ServiceProvider
{
    /**
     * The address where customer support e-mails should be sent.
     *
     * @var string
     */
    protected $sendSupportEmailsTo = 's.woodcock@thekidsnetwork.org.uk';

    /**
     * Finish configuring Spark for the application.
     *
     * @return void
     */
    public function booted() {
        
    }
}
