<?php

namespace App\Controllers\Auth;

class RegisterController {
    public function index() {
        if (isset($_SESSION['user']['connected']) && $_SESSION['user']['connected'] === 1) {
            header('Location: /');
            exit;
        }

        require '../views/Auth/register.php';
    }

    public function submit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $_SESSION['user']['connected'] = 1;
            header('Location: /');
            exit;
        }
    }
}