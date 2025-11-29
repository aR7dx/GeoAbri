<?php

namespace App\Models\Auth;

use PDO;
use App\Config\Database;
use App\Exceptions\Database\DatabaseConnectionException;

class User {
    private PDO $db;

    public function __construct() {
        try
        {
            $this->db = Database::getInstance()->getConnection();
        }
        catch (PDOException $e) 
        {
            throw new DatabaseConnectionException();
        }
    }

    public function findByEmail(string $email) {
        $donnees = null;
        try
        {
            $sql = "SELECT u.user_id, u.nom, u.prenom, u.email, u.telephone, u.ville, u.code_postal, u.password_hash, r.name as role_name
                    FROM GEO_UTILISATEURS u LEFT JOIN GEO_ROLES r ON u.role_id = r.role_id
                    WHERE u.email = :email LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':email' => $email]);
            $donnees = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e) 
        {
            throw new DatabaseConnectionException();
        }
        return $donnees;
    }

    public function findById(int $id)
    {
        $donnees = null;
        try
        {
            $sql = "SELECT u.user_id, u.nom, u.prenom, u.email, u.telephone, u.ville, u.code_postal, r.name as role_name
                    FROM GEO_UTILISATEURS u LEFT JOIN GEO_ROLES r ON u.role_id = r.role_id
                    WHERE u.id = :id LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            $donnees = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e) 
        {
            throw new DatabaseConnectionException();
        }
        return $donnees;
    }

    public function getPermissions(int $userId)
    {
        $donnees = null;
        try
        {
            $sql ="SELECT p.name FROM GEO_PERMISSIONS p
                JOIN GEO_ROLE_PERMISSIONS rp ON p.permission_id = rp.permission_id
                JOIN GEO_ROLES r ON rp.role_id = r.role_id
                JOIN GEO_UTILISATEURS u ON u.role_id = r.role_id
                WHERE u.user_id = :uid";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':uid' => $userId]);
            $donnees = $stmt->fetchAll(PDO::FETCH_COLUMN);
        }
        catch (PDOException $e) 
        {
            throw new DatabaseConnectionException();
        }
        return $donnees;
    }

    public function createUser(string $nom = null, string $prenom = null, string $email, string $telephone, string $ville, string $codePostal, string $password, ?int $roleId = null)
    {
        try
        {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO GEO_UTILISATEURS (nom, prenom, email, telephone, ville, code_postal, password_hash, role_id) VALUES (:nom, :prenom, :email, :telephone, :ville, :code_postal, :hash, :role_id)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':email' => $email,
                ':telephone' => $telephone,
                ':ville' => $ville,
                ':code_postal' => $codePostal,
                ':hash' => $hash,
                ':role_id' => $roleId
            ]);
        }
        catch (PDOException $e) 
        {
            throw new DatabaseConnectionException();
        }
        return $this->db->lastInsertId();
    }

    public function deleteUser($userId) 
    {
        try
        {
            $sql = "DELETE FROM GEO_UTILISATEURS WHERE user_id = :uid";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':uid' => $userId]);
            return true;
        }
        catch (PDOException $e) 
        {
            throw new DatabaseConnectionException();
        }
        return false;
    }
}