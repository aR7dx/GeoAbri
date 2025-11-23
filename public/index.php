<?php
require '../vendor/autoload.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

$router = new AltoRouter();

/* création des routes */
$router->map('GET', '/', 'Home\HomeController@index', 'home');
$router->map('GET', '/map', 'Map\InteractiveMapController@index', 'map');
$router->map('GET', '/auth/register', 'Auth\RegisterController@index', 'register');
$router->map('POST','/auth/register', 'Auth\RegisterController@submit', 'register_submit');
$router->map('GET', '/auth/logout', 'Auth\LogoutController@disconnect', 'logout');
$router->map('GET', '/account', 'Account\AccountController@index', 'account');
$router->map('GET', '/api/map-equipements', 'API\EquipementsAPIController@index', 'map-equipements');
$router->map('GET', '/api/map-suggestions', 'API\SuggestionsAPIController@index', 'map-suggestions');

/* vérification de la route */
$match = $router->match();

if (is_array($match)) {
    
    if (is_callable($match['target'])) {
        call_user_func_array($match['target'], $match['params']);
    } 
    else {
        list($controller, $method) = explode('@', $match['target']);

        /* initialisation du controleur */
        $controller = 'App\\Controllers\\' . $controller;
        $controllerInstance = new $controller();

        /* demarrage de la session si nécessaire */
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        call_user_func_array([$controllerInstance, $method], $match['params']);
    }
}
else {
    require '../Views/Errors/pagenotfound.php';
}

?>
