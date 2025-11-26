<?php

namespace App\Models\Auth;

use PDO;
use App\Config\Database;

class User {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findByEmail(string $email) {
        $sql = "SELECT u.user_id, u.email, u.password_hash, u.prenom, u.nom, r.name as role_name
                FROM GEO_UTILISATEURS u LEFT JOIN GEO_ROLES r ON u.role_id = r.role_id
                WHERE u.email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findById(int $id)
    {
        $sql = "SELECT u.user_id, u.email, u.prenom, u.nom, r.name as role_name
                FROM GEO_UTILISATEURS u LEFT JOIN GEO_ROLES r ON u.role_id = r.role_id
                WHERE u.id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPermissions(int $userId)
    {
        $sql ="SELECT p.name FROM GEO_PERMISSIONS p
            JOIN GEO_ROLE_PERMISSIONS rp ON p.permission_id = rp.permission_id
            JOIN GEO_ROLES r ON rp.role_id = r.role_id
            JOIN GEO_UTILISATEURS u ON u.role_id = r.role_id
            WHERE u.user_id = :uid";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':uid' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function createUser(string $email, string $password, ?string $prenom = null, ?string $nom = null, ?int $roleId = null)
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO GEO_UTILISATEURS (email, password_hash, prenom, nom, role_id) VALUES (:email, :hash, :prenom, :nom, :role_id)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':email' => $email,
            ':hash' => $hash,
            ':prenom' => $prenom,
            ':nom' => $nom,
            ':role_id' => $roleId
        ]);
        return $this->db->lastInsertId();
    }
}