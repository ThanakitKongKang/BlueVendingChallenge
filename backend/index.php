<?php
// Autoload classes using a PSR-4 compliant autoloader
spl_autoload_register(function ($className) {
    require_once __DIR__ . '/classes/' . str_replace('\\', '/', $className) . '.php';
});

use App\Router;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');

$router = new Router();

// Define routes
$router->post('/coins/insert', 'MainController@insertCoins');
$router->get('/reset', 'MainController@resetToDefault');
$router->post('/products/buy', 'MainController@selectProduct');
$router->get('/products', 'MainController@getAllProducts');
$router->get('/user/money', 'MainController@getUserMoney');
$router->get('/cancel', 'MainController@resetUserCoins');
$router->get('/machine/coins', 'MainController@getMachineCoins');

$router->get('/admin/restock', 'MainController@restockProduct');
$router->get('/admin/reset-changes', 'MainController@resetChanges');

$router->get('/healthcheck', 'MainController@healthcheck');

// Handle the request
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
