<?php

/**
 * Laravel - A PHP Framework For Web Artisans.
 * For same directory structure
 */
define('LARAVEL_START', microtime(true));

// If Laravel is in the same directory
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);
$response->send();
$kernel->terminate($request, $response);