<?php

namespace App\Controllers\Auth;

class LogonController {
    public function index() {
        if (isset($_SESSION['user']['connected']) && $_SESSION['user']['connected'] === 1) {
            header('Location: /');
            exit;
        }

        global $router;

        require dirname(__DIR__) . '/../Views/Auth/logon.php';
    }

    public function submit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            header('Location: /');
            exit;
        }
    }
}