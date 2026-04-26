<?php

use App\Console\Commands\AutoCancelUnpaidOrders;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Batalkan otomatis pesanan tidak dibayar > 24 jam — jalan tiap jam
Schedule::command(AutoCancelUnpaidOrders::class)->hourly();
