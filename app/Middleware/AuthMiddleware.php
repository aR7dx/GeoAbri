<?php

namespace App\Middleware;

class AuthMiddleware {

    /**
     * Vérifie si l'utilisateur est connecté.
     */
    public static function handle() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user']) || empty($_SESSION['user']['id']) || !isset($_SESSION['user']['connected']) || $_SESSION['user']['connected'] !== 1)
        {
            header('Location: /auth/login');
            exit;
        }
    }

    /**
     * Vérifie qu'un utilisateur n'est pas déjà connecté.
     */
    public static function redirectIfAuthenticated(string $redirect = '/') {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_SESSION['user']['connected']) && $_SESSION['user']['connected'] === 1) {
        header('Location: ' . $redirect);
        exit;
    }
}

    /**
     * Vérifie si utilisateur connecté et retourne user array
     */
    public static function user() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return $_SESSION['user'] ?? null;
    }
}