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
        AuthMiddleware::redirectIfAuthenticated("/");
        global $router;

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $email = htmlspecialchars($_POST['email']) ?? null;
        $password = htmlspecialchars($_POST['password']) ?? null;

        if (!$email || !$password) {
            header('Location: ' . $router->generate('login'));
            exit;
        }

        // recuperation de l'utilisateur si il existe
        $user = $this->userModel->findByEmail($email);

        if (!$user || empty($user['password_hash'])) {
            $_SESSION['notification']['not_valid_connection'] = 1;
            header('Location: ' . $router->generate('login'));
            exit;
        }

        if (!password_verify($password, $user['password_hash'])) {
            $_SESSION['notification']['not_valid_connection'] = 1;
            header('Location: ' . $router->generate('login'));
            exit;
        }

        // recuperation des permmissions de l'utilisateur
        $permissions = $this->userModel->getPermissions((int)$user['user_id']);

        $_SESSION['user'] = [
            'id' => (int)$user['user_id'],
            'user_id' => (int)$user['user_id'], // Ajout pour rétrocompatibilité
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

        header('Location: ' . $router->generate('home'));
        exit;
    }
}