<?php

namespace App\Controllers\Account;

class AccountController {
    public function index() {

        if (!isset($_SESSION['user']['connected'])) {
            header('Location: /');
            exit;
        }

        global $router;
        $titre = "Mon compte - Équipements d'urgences";

        require '../views/Account/account.php';
    }
}