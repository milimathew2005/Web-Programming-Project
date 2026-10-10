<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/**
 * Schedule daily overdue fine updates for active loans.
 */
Schedule::command('library:update-fines')->daily();

/**
 * Schedule hourly check for expiring student book reservations.
 */
Schedule::command('library:expire-reservations')->hourly();
