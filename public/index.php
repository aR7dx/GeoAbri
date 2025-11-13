<?php
require '../vendor/autoload.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

$router = new AltoRouter();

$router->map('GET', '/', 'HomeController@index', 'home');
$router->map('GET', '/map', 'Map\MapViewController@index', 'map');
$router->map('GET', '/auth/register', 'Auth\RegisterController@index', 'register');
#$router->map('GET', '/auth/login', 'Auth\LoginController@index', 'login');

$match = $router->match();

if (is_array($match)) {

    // On vérifie si la cible est une fonction de controlleur
    
    if (is_callable($match['target'])) {
        call_user_func_array($match['target'], $match['params']);
    } 
    else {
        list($controller, $method) = explode('@', $match['target']);

        $controller = 'App\\Controllers\\' . $controller;
        $controllerInstance = new $controller();

        call_user_func_array([$controllerInstance, $method], $match['params']);
    }
}
else {
    require '../views/errors/pagenotfound.php';
}

?>

