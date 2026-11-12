<?php

/**
 * Serverless entry point for Vercel (vercel-php runtime).
 * Forwards every request to the normal Laravel front controller,
 * after preparing the ephemeral sqlite database in /tmp on cold start.
 */

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel...
/** @var \Illuminate\Foundation\Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

// --- Serverless sqlite seed-on-boot -------------------------------------
// The filesystem is read-only except /tmp, and each function instance
// gets its own /tmp. If the sqlite file does not exist yet, create it,
// migrate and seed it once for this instance, then serve normally.
$dbPath = getenv('DB_DATABASE') ?: '/tmp/database.sqlite';
if (str_starts_with($dbPath, '/tmp/') && !file_exists($dbPath)) {
    touch($dbPath);
    try {
        $kernel = $app->make(Kernel::class);
        $kernel->bootstrap();
        Artisan::call('migrate', ['--force' => true]);
        Artisan::call('db:seed', ['--force' => true]);
    } catch (\Throwable $e) {
        // Let the request continue; Laravel will surface any real
        // database error through its normal exception handling.
    }
}
// -------------------------------------------------------------------------

$app->handleRequest(Request::capture());
