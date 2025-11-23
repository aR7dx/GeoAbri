<?php

namespace App\Controllers\Account;

class AccountController {
    public function index() {

        if (!isset($_SESSION['user']['connected']) || $_SESSION['user']['connected'] === 0) {
            header('Location: /');
            exit;
        }

        global $router;
        $titre = "Mon compte - Équipements d'urgences";

        require dirname(__DIR__) . '/../Views/Account/account.php';
    }
}