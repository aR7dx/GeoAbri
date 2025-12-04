<?php

namespace App\Controllers\Auth;

use PDO;
use App\Config\Database;
use App\Models\Auth\User;
use App\Middlewares\AuthMiddleware;
use App\Exceptions\Database\DatabaseConnectionException;

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

        $nom = htmlspecialchars($_POST['nom']) ?? null;
        $prenom = htmlspecialchars($_POST['prenom']) ?? null;
        $email = htmlspecialchars($_POST['email']) ?? null;
        $telephone = htmlspecialchars($_POST['telephone']) ?? null;
        $ville = htmlspecialchars($_POST['ville']) ?? null;
        $codePostal = htmlspecialchars($_POST['codePostal']) ?? null;
        $password = htmlspecialchars($_POST['password']) ?? null;
        $confirmPassword = htmlspecialchars($_POST['confirmPassword']) ?? null;

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

        // crée le user avec role par defaut sur 'Utilisateur'
        $role = null;
        try 
        {
            $sql = "SELECT role_id FROM GEO_ROLES WHERE name = 'Utilisateur' LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $role = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            throw new DatabaseConnectionException();
        }
        $roleId = $role['role_id'] ?? null;

        $newUserId = $this->userModel->createUser($nom, $prenom, $email, $telephone, $ville, $codePostal, $password, $roleId);

        $_SESSION['user'] = [
            'id' => (int)$newUserId,
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'telephone' => $telephone,
            'ville' => $ville,
            'code_postal' => $codePostal,
            'role' => 'Utilisateur',
            'permissions' => $this->userModel->getPermissions((int)$newUserId),
            'connected' => 1
        ];

        header('Location: /');
        exit;
    }
}