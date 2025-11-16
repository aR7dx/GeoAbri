<?php

namespace App\Controllers\Auth;

class LogoutController {
    public function disconnect() {
        $_SESSION['user']['connected'] = 0;
        header('Location: /');
        exit;
    }
}