<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Domains\Documents\Events\DocumentDeleted' => [
            'App\Domains\EventHandling\Listeners\DocumentDeletedListener'
        ],
        'App\Domains\SessionReports\Events\SessionReportDeleted' => [
            'App\Domains\EventHandling\Listeners\SessionReportDeletedListener'
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
