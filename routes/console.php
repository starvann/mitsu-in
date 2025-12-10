<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('app:auto-alpha')->dailyAt(9);
Schedule::command('app:auto-alpha')->dailyAt(13);
Schedule::command('app:auto-alpha')->dailyAt(17);
Schedule::command('app:auto-alpha')->dailyAt(21);