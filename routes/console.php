<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Automated Backup Schedule
|--------------------------------------------------------------------------
| Daily backups at 2:00 AM for database and medical files
| Cleanup old backups weekly to save disk space
| Health monitoring to ensure backups are working
*/

// Daily full backup at 2:00 AM (off-peak hours)
Schedule::command('backup:clean')->daily()->at('01:30');
Schedule::command('backup:run')->daily()->at('02:00');

// Monitor backup health daily at 3:00 AM
Schedule::command('backup:monitor')->daily()->at('03:00');

// Additional monitoring for critical errors
Schedule::command('backup:monitor')->weekly()->mondays()->at('09:00');

/*
|--------------------------------------------------------------------------
| Queue Worker Monitoring
|--------------------------------------------------------------------------
| Restart queue workers nightly to prevent memory leaks
*/
Schedule::command('queue:restart')->daily()->at('03:30');

/*
|--------------------------------------------------------------------------
| Health Check Endpoint
|--------------------------------------------------------------------------
| Built-in Laravel health check at /up route (configured in bootstrap/app.php)
*/
