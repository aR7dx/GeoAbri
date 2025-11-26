<?php

namespace App\Controllers\Account;

use App\Middleware\AuthMiddleware;

class AccountController {

    public function index() {
        AuthMiddleware::handle();

        global $router;
        $titre = "Mon compte - Équipements d'urgences";

        require dirname(__DIR__) . '/../Views/Account/account.php';
    }
}