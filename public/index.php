<?php
require '../vendor/autoload.php';

// TODO 
// creer une page qui catch quand il y a des erreurs 500 et enleve les erreurs en dessous
ini_set('display_errors', 1);
error_reporting(E_ALL);

$router = new AltoRouter();

/* création des routes */
$router->map('GET', '/', 'Home\HomeController@index', 'home');
$router->map('GET', '/map', 'Map\InteractiveMapController@index', 'map');
$router->map('GET', '/auth/login', 'Auth\LoginController@index', 'login');
$router->map('POST', '/auth/login', 'Auth\LoginController@login', 'login_submit');
$router->map('GET', '/auth/register', 'Auth\RegisterController@index', 'register');
$router->map('POST','/auth/register', 'Auth\RegisterController@register', 'register_submit');
$router->map('GET', '/auth/logout', 'Auth\LogoutController@logout', 'logout');
$router->map('GET', '/account', 'Account\AccountController@index', 'account');
$router->map('GET', '/account/edit', 'Account\EditAccountController@index', 'edit_account');
$router->map('GET', '/api', 'API\APIController@index', 'api');
$router->map('GET', '/api/map-equipements', 'API\EquipementsAPIController@index', 'map-equipements');
$router->map('GET', '/api/map-suggestions', 'API\SuggestionsAPIController@index', 'map-suggestions');
$router->map('GET', '/dashboard', 'Admin\DashboardController@index', 'dashboard');


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
    require '../app/Views/Errors/404notfound.php';
}

?>
