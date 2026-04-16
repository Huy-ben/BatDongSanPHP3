<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\NotifyExpiringListingPackages;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('packages:notify-expiring', function () {
    return app(NotifyExpiringListingPackages::class)->handle();
})->purpose('Notify users when listing packages are expiring soon');

Schedule::command('packages:notify-expiring')->dailyAt('08:00');
