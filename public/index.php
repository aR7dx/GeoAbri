<?php
require '../vendor/autoload.php';

use App\Exceptions\Database\DatabaseConnectionException;

// TODO 
// creer une page qui catch quand il y a des erreurs 500 et enleve les erreurs en dessous
ini_set('display_errors', 1);
error_reporting(E_ALL);

$router = new AltoRouter();

//home
$router->map('GET', '/', 'Home\HomeController@index', 'home');
//map
$router->map('GET', '/map', 'Map\InteractiveMapController@index', 'map');
//authentification
$router->map('GET', '/auth/login', 'Auth\LoginController@index', 'login');
$router->map('POST','/auth/login', 'Auth\LoginController@login', 'login_submit');
$router->map('GET', '/auth/register', 'Auth\RegisterController@index', 'register');
$router->map('POST','/auth/register', 'Auth\RegisterController@register', 'register_submit');
$router->map('GET', '/auth/logout', 'Auth\LogoutController@logout', 'logout');
//account
$router->map('GET', '/account', 'Account\AccountController@index', 'account');
$router->map('POST', '/account', 'Account\AccountController@delete', 'delete_account');
$router->map('GET', '/account/edit', 'Account\EditAccountController@index', 'edit_account');
$router->map('POST', '/account/edit', 'Account\EditAccountController@edit', 'edit_account_submit');
//api public
$router->map('GET', '/api', 'API\APIController@index', 'api');
$router->map('GET', '/api/map/equipements', 'API\EquipementsAPIController@index', 'map-equipements');
$router->map('GET', '/api/map/suggestions', 'API\SuggestionsAPIController@index', 'map-suggestions');
$router->map('GET', '/api/map/filters', 'API\FiltersAPIController@index', 'map-filters');
//dashboard
$router->map('GET', '/dashboard', 'Admin\DashboardController@index', 'dashboard');
$router->map('GET', '/dashboard/equipements', 'Admin\EquipementsManagementController@index', 'admin_equipements');
$router->map('POST','/dashboard/equipements', 'Admin\EquipementsManagementController@handler', 'equipement_submit');
$router->map('GET', '/dashboard/users', 'Admin\DashboardController@users', 'users_management');
$router->map('GET', '/dashboard/pendings', 'Admin\PendingsManagementController@index', 'admin_pendings');
$router->map('POST','/dashboard/pendings', 'Admin\PendingsManagementController@handler', 'pendings_submit');





// vérification de la route
$match = $router->match();

if (is_array($match)) {
    
    if (is_callable($match['target'])) {
        call_user_func_array($match['target'], $match['params']);
    } 
    else {
        try
        {
            list($controller, $method) = explode('@', $match['target']);

            // initialisation du controleur
            $controller = 'App\\Controllers\\' . $controller;
            $controllerInstance = new $controller();

            // demarrage de la session si nécessaire
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            call_user_func_array([$controllerInstance, $method], $match['params']);
        }
        catch (DatabaseConnectionException $e) {
            // Pas de connexion avec la base de données
            require '../app/Views/Errors/nodatabase.php';
        }
        catch (Exception $e) {
            // Code 500 : Erreur Serveur
            require '../app/Views/Errors/500servererror.php';
        }
        
    }
}
else {
    // Code 404 : Page introuvable
    require '../app/Views/Errors/404notfound.php';
}

?>
