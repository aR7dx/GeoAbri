<?php

namespace App\Middleware;

use App\Middleware\AuthMiddleware;

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

        if (!empty($user['role']) && $user['role'] === 'admin') {
            return true;
        }

        $perms = $user['permissions'] ?? [];

        if (!in_array($permissionName, $perms)) {
            if (!empty($options['redirect'])) {
                header('Location: ' . $options['redirect']);
                exit;
            } else {
                http_response_code(403);
                echo "403 - Accès refusé";

                // TODO il va falloir améliorer ca en faisant une vraie page à part pour l'erreur 403 comme avec l'erreur 404
                exit;
            }
        }

        return true;
    }
}