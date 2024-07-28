<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('queue:restart')->everyFiveMinutes();
Schedule::command('queue:work')->name('queue_work_name')->withoutOverlapping()->runInBackground();
