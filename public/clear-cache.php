<?php
/**
 * Helper: clear Laravel cache via browser
 * HAPUS file ini setelah dipakai!
 * Akses: http://catering-family-jakarta.test/clear-cache.php
 */
if (php_sapi_name() !== 'cli') {
    // Jalankan dari browser
    define('LARAVEL_START', microtime(true));
    require __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

    $output = [];
    $commands = [
        'config:clear',
        'cache:clear',
        'route:clear',
        'view:clear',
    ];

    foreach ($commands as $cmd) {
        $kernel->call($cmd);
        $output[] = "✅ php artisan $cmd — done";
    }

    echo '<pre style="font-family:monospace;font-size:14px;padding:20px;">';
    echo "<b>Laravel Cache Cleared</b>\n\n";
    echo implode("\n", $output);
    echo "\n\n<b style='color:red'>⚠️  HAPUS file ini sekarang! (public/clear-cache.php)</b>";
    echo '</pre>';
} else {
    echo "Jalankan via browser.\n";
}
