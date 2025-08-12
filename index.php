<?php
// Composer autoload
require_once __DIR__ . '/vendor/autoload.php';

use App\Router;

// Start session for flash messages
session_start();

// Simple router
$router = new Router();
$router->dispatch();
