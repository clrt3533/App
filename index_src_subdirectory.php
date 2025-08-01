<?php

/**
 * Laravel - A PHP Framework For Web Artisans.
 * For src subdirectory structure
 */
define('LARAVEL_START', microtime(true));

// If Laravel is in /src/ subdirectory
require __DIR__.'/src/vendor/autoload.php';
$app = require_once __DIR__.'/src/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);
$response->send();
$kernel->terminate($request, $response);