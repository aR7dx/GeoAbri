<?php

namespace App\Controllers\Auth;

class RegisterController {
    public function index() {

        /*
        if ($_POST['submit']) {
            header('Location: /');
            exit;
        }
        */

        require '../views/auth/register.php';
    }
}