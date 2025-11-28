<?php

namespace App\Controllers\Auth;

use PDO;
use App\Config\Database;
use App\Models\Auth\User;
use App\Middlewares\AuthMiddleware;

class LoginController {
    private User $userModel;
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
        $this->userModel = new User();
    }

    public function index() {
        AuthMiddleware::redirectIfAuthenticated("/");

        global $router;

        require dirname(__DIR__) . '/../Views/Auth/login.php';
    }

    public function login() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;

        if (!$email || !$password) {
            header('Location: /auth/login');
            exit;
        }

        $user = $this->userModel->findByEmail($email);
        if (!$user || empty($user['password_hash'])) {
            header('Location: /auth/login');
            exit;
        }

        if (!password_verify($password, $user['password_hash'])) {
            header('Location: /auth/login');
            exit;
        }

        $permissions = $this->userModel->getPermissions((int)$user['user_id']);

        $_SESSION['user'] = [
            'id' => (int)$user['user_id'],
            'nom' => $user['nom'] ?? null,
            'prenom' => $user['prenom'] ?? null,
            'email' => $user['email'],
            'telephone' => $user['telephone'] ?? null,
            'ville' => $user['ville'] ?? null,
            'code_postal' => $user['code_postal'] ?? null,
            'role' => $user['role_name'] ?? null,
            'permissions' => $permissions,
            'connected' => 1
        ];

        header('Location: /');
        exit;
    }
}