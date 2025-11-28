<?php

namespace App\Controllers\Account;

use App\Models\Auth\User;
use App\Middlewares\AuthMiddleware;

class AccountController {

    public function index() {
        AuthMiddleware::handle();

        global $router;
        $titre = "Mon compte - GeoAbri";

        require dirname(__DIR__) . '/../Views/Account/account.php';
    }

    public function delete() {
        AuthMiddleware::handle();
        global $router;
        
        $userId = $_SESSION['user']['id'];

        $userModel = new User();
        $userModel->deleteUser($userId);

        session_unset();
        session_destroy();

        header("Location: " . $router->generate('home'));
        exit;
    }
}