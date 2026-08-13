<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// When the site is served from a subfolder through the root .htaccess, the URL
// has no "/public" in it but the script still lives in public/. Point SCRIPT_NAME
// one level up so Laravel strips the subfolder instead of treating it as a route.
if (isset($_SERVER['SCRIPT_NAME'], $_SERVER['REQUEST_URI'])
    && str_ends_with(dirname($_SERVER['SCRIPT_NAME']), '/public')
    && !str_contains($_SERVER['REQUEST_URI'], '/public/')) {
    $_SERVER['SCRIPT_NAME'] = dirname($_SERVER['SCRIPT_NAME'], 2).'/index.php';
    $_SERVER['PHP_SELF'] = $_SERVER['SCRIPT_NAME'];
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
