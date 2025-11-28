<?php

namespace App\Controllers\Auth;

class LogoutController {
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            setcookie(session_name(), '', time() - 42000);
        }

        session_unset();
        session_destroy();
        header('Location: /auth/login');
        
        exit;
    }
}