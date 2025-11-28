<?php

namespace App\Middlewares;

use App\Middlewares\AuthMiddleware;

class PermissionMiddleware {

    /**
     * Vérifie que l'utilisateur a la permission demandée
     * $permissionName : string, ex: 'edit_equipment'
     * $options : ['redirect' => '/no-permission'] optionnel
     */
    public static function handle(string $permissionName, array $options = []) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        AuthMiddleware::handle();

        $user = AuthMiddleware::user();
        if (!$user) {
            header('Location: /auth/login');
            exit;
        }

        if (!empty($user['role']) && $user['role'] === 'Administrateur') {
            return true;
        }

        $perms = $user['permissions'] ?? [];

        if (!in_array($permissionName, $perms)) {
            if (!empty($options['redirect'])) {
                header('Location: ' . $options['redirect']);
                exit;
            } else {
                global $router;
                require '../app/Views/Errors/403forbidden.php';
                exit;
            }
        }

        return true;
    }
}