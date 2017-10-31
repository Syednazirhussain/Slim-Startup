<?php
ini_set( 'session.use_only_cookies', TRUE );                
ini_set( 'session.use_trans_sid', FALSE );
ini_set( 'session.cookie_lifetime', 1200 ); // 1200 sec or 20 mintss

// Set the cookie name
session_name('Bootstap');

$limit = 0;
$path  = "/";
$domain = $_SERVER["HTTP_HOST"];
$secure = false;

// Set SSL level
$https = isset($secure) ? $secure : isset($_SERVER['HTTPS']);

// Set session cookie options
session_set_cookie_params($limit, $path, $domain, $https, true);

session_start();

define('isIncludedTrue', 1);

defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);



if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
   //echo 'This is a server using Windows!';
   define('ROOT', 'C:/xampp/htdocs/Test');
}



$GLOBALS['page_start_time'] = microtime(true);

require_once (ROOT . DS . 'config' . DS . 'config.php');
// slim autoload file
require_once (ROOT . DS . 'vendor' . DS . 'autoload.php');
// load routes file 
require_once (ROOT . DS . 'config' . DS . 'routes.php');

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


use \app\controllers;



// Create Slim app
$app = new \Slim\App($slimConfig);

// Fetch DI Container
$container = $app->getContainer();



require_once (ROOT . DS . 'app/controllers/ApplicationController.php');

require_once (ROOT . DS . 'app/filters/application_filters.php');

// Register Blade View helper
$container['view'] = function ($container) {
    return new \Slim\Views\Blade(
        $container['settings']['renderer']['blade_template_path'],
        $container['settings']['renderer']['blade_cache_path']
    );
};

// 404 page added
$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
//        return $c['response']
//            ->withStatus(404)
//            ->withHeader('Content-Type', 'text/html')
//            ->write('Page not found');
        return $c['view']->render($response, 'error_pages/404')->withStatus(404);
    };
};


$container['HomeController'] = function($c) {
    // require(ROOT . DS . 'app/controllers/HomeController.php');
    $view = $c->get("view"); // retrieve the 'view' from the container
    #$flash = $c->get("flash");
    #return new HomeController($view,$flash);
    return new HomeController($view);
};


$container['LoginController'] = function($c) {
    // require(ROOT . DS . 'app/controllers/LoginController.php');
    $view = $c->get("view"); // retrieve the 'view' from the container
    #$flash = $c->get("flash");
    return new LoginController($view);
};




// configure routes for app
$MainRouter = new MainRouter();
$MainRouter->configure($app);
$app->run();
