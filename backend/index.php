<?php
// Autoload classes using a PSR-4 compliant autoloader
spl_autoload_register(function ($className) {
    require_once __DIR__ . '/classes/' . str_replace('\\', '/', $className) . '.php';
});

use App\Services\Database;
use App\Router;

// Trigger database setup by calling getConnection()
// Database::initDatabase();

$router = new Router();

// Define routes
$router->post('/insertCoins', 'MainController@insertCoins');
$router->get('/reset', 'MainController@resetToDefault');
$router->get('/selectProduct/{productId}/{userMoney}', 'MainController@selectProduct');
$router->get('/healthcheck', 'MainController@healthcheck');

// Handle the request
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
