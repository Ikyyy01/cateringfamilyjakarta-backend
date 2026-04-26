<?php

namespace App\Providers;

use App\Events\OrderCreated;
use App\Events\OrderStatusChanged;
use App\Events\PaymentStatusChanged;
use App\Listeners\SendOrderCreatedNotifications;
use App\Listeners\SendOrderStatusNotification;
use App\Listeners\SendPaymentStatusNotification;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Observers\MenuObserver;
use App\Observers\OrderObserver;
use App\Observers\PaymentObserver;
use App\Observers\UserObserver;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // ── Observers — tulis ke activity_logs otomatis ────────
        Order::observe(OrderObserver::class);
        Payment::observe(PaymentObserver::class);
        Menu::observe(MenuObserver::class);
        User::observe(UserObserver::class);

        // ── Rate limiting ──────────────────────────────────────
        RateLimiter::for('order', function (Request $request) {
            return Limit::perMinute(5)->by(
                $request->user()?->id ?: $request->ip()
            );
        });

        RateLimiter::for('payment_upload', function (Request $request) {
            return Limit::perMinute(3)->by(
                $request->user()?->id ?: $request->ip()
            );
        });

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->ip());
        });

        // ── Event listeners ────────────────────────────────────
        Event::listen(OrderCreated::class,         SendOrderCreatedNotifications::class);
        Event::listen(OrderStatusChanged::class,   SendOrderStatusNotification::class);
        Event::listen(PaymentStatusChanged::class, SendPaymentStatusNotification::class);
    }
}
