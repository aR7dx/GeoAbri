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

        global $router;
        
        require dirname(__DIR__) . '/../Views/Auth/register.php';
    }

    public function register() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $type = strtolower(htmlspecialchars($_POST['type'])) ?? null;
        $nom = ucfirst(strtolower(htmlspecialchars($_POST['nom']))) ?? null;
        $prenom = ucfirst(strtolower(htmlspecialchars($_POST['prenom']))) ?? null;
        $email = strtolower(htmlspecialchars($_POST['email'])) ?? null;
        $telephone = htmlspecialchars($_POST['telephone']) ?? null;
        $ville = ucfirst(strtolower(htmlspecialchars($_POST['ville']))) ?? null;
        $codePostal = htmlspecialchars($_POST['codePostal']) ?? null;
        $password = htmlspecialchars($_POST['password']) ?? null;
        $confirmPassword = htmlspecialchars($_POST['confirmPassword']) ?? null;

        if (!$type || !$email || !$password || !$confirmPassword) {
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
            $sql = "SELECT role_id FROM GEO_ROLES WHERE lower(name) = 'utilisateur' LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $role = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            throw new DatabaseConnectionException();
        }
        $roleId = $role['role_id'] ?? null;

        $this->db->beginTransaction();
        $newUserId = null;

        try 
        {
            $newUserId = $this->userModel->createUser($nom, $prenom, $email, $telephone, $ville, $codePostal, $password, $roleId);

            if ($type !== 'utilisateur') {
                $sql = "INSERT INTO GEO_DEMANDES (id_type_demande, nom, description, date_debut, demandeur_id, status) VALUES ((SELECT id_type_demande FROM GEO_TYPE_DEMANDES WHERE lower(alias) = 'request_" . strtolower($type) ."'), :nom, :description, :date_deb, :demandeur_id, :status)";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([':nom' => ("Demande acceptation " . $type),
                                ':description' => ("L'utilisateur " . $nom . " " . $prenom . " demande à créer un compte " . $type . "."),
                                ':date_deb' => date('Y-m-d H:i:s'),
                                ':demandeur_id' => $newUserId,
                                ':status' => "En attente"
                            ]);
            }

            $this->db->commit();
        }
        catch (PDOException $e) 
        {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw new DatabaseConnectionException();
        }

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