<?php

namespace App\Controllers\Auth;

use PDO;
use App\Config\Database;
use App\Models\Auth\User;
use App\Middlewares\AuthMiddleware;

class RegisterController {
    private User $userModel;
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->userModel = new User();
    }

    public function index() {
        AuthMiddleware::redirectIfAuthenticated("/");
        
        require dirname(__DIR__) . '/../Views/Auth/register.php';
    }

    public function register() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;
        $confirmPassword = $_POST['confirmPassword'] ?? null;
        $nom = $_POST['nom'] ?? null;
        $prenom = $_POST['prenom'] ?? null;

        // TODO
        // recuperer les autres infos plus tard

        if (!$email || !$password || !$confirmPassword) {
            header('Location: /auth/register');
            exit;
        }

        if ($password !== $confirmPassword) {
            header('Location: /auth/register');
            exit;
        }

        // mail déjà utilisé
        if ($this->userModel->findByEmail($email)) {
            header('Location: /auth/register');
            exit;
        }

        // crée le user avec role par defaut sur 'user'
        $sql = "SELECT role_id FROM GEO_ROLES WHERE name = 'user' LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $role = $stmt->fetch(PDO::FETCH_ASSOC);
        $roleId = $role['role_id'] ?? null;

        $newUserId = $this->userModel->createUser($email, $password, $prenom, $nom, $roleId);

        $_SESSION['user'] = [
            'id' => (int)$newUserId,
            'email' => $email,
            'prenom' => $prenom,
            'nom' => $nom,
            'role' => 'user',
            'permissions' => $this->userModel->getPermissions((int)$newUserId),
            'connected' => 1
        ];

        header('Location: /');
        exit;
    }
}