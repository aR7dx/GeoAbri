<?php

namespace App\Models\Admin;

use PDO;
use App\Config\Database;
use App\Exceptions\Database\DatabaseConnectionException;

class Pending {
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

    public function getActivePendings() {
        try
        {
            $sql = "SELECT * FROM GEO_DEMANDES WHERE date_fin IS NULL";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e) 
        {
            throw new DatabaseConnectionException();
        }
        return null;
    }

    public function getPendingById($id) {
        try {
            $sql = "SELECT dem.*, tdem.alias FROM GEO_DEMANDES dem JOIN GEO_TYPE_DEMANDES tdem ON dem.id_type_demande = tdem.id_type_demande WHERE id_demande = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e) 
        {
            throw new DatabaseConnectionException();
        }
    }

    public function accept($id) {
        try {
            $pending = $this->getPendingById($id);

            if ($pending === false) return 0;

            $this->db->beginTransaction();

            $sql = "UPDATE GEO_DEMANDES SET date_fin = :date_fin, status = :status WHERE id_demande = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':date_fin' => date('Y-m-d H:i:s'),
                            ':status' => 'Acceptée',
                            ':id' => $pending['id_demande']
                        ]);

            if (isset($pending['alias'])) {
                switch ($pending['alias']) {
                    case 'request_association':
                        $sql = "UPDATE GEO_UTILISATEURS SET role_id = (SELECT role_id FROM GEO_ROLES WHERE lower(name) = 'association') WHERE user_id = :uid";
                        $stmt = $this->db->prepare($sql);
                        $stmt->execute([':uid' => $pending['demandeur_id']]);

                        break;
                    case 'request_collectivite':
                        $sql = "UPDATE GEO_UTILISATEURS SET role_id = (SELECT role_id FROM GEO_ROLES WHERE lower(name) = 'collectivite') WHERE user_id = :uid";
                        $stmt = $this->db->prepare($sql);
                        $stmt->execute([':uid' => $pending['demandeur_id']]);

                        break;
                    case 'request_club':
                        $sql = "UPDATE GEO_UTILISATEURS SET role_id = (SELECT role_id FROM GEO_ROLES WHERE lower(name) = 'club') WHERE user_id = :uid";
                        $stmt = $this->db->prepare($sql);
                        $stmt->execute([':uid' => $pending['demandeur_id']]);

                        break;
                    default:
                        if ($this->db->inTransaction()) {
                            $this->db->rollBack();
                        }
                        return 0;
                        break;
                }
            }
            else {
                if ($this->db->inTransaction()) {
                    $this->db->rollBack();
                }
                return 0; 
            }

            $this->db->commit();
            return 1;
            
        }
        catch (PDOException $e) 
        {
            // rollback si une transaction a été démarrée
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw new DatabaseConnectionException();
        }
        return 0;
    }

    public function deny($id) {
        try {
            $pending = $this->getPendingById($id);

            if ($pending === false) return 0;

            $this->db->beginTransaction();

            $sql = "UPDATE GEO_DEMANDES SET date_fin = :date_fin, status = :status WHERE id_demande = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':date_fin' => date('Y-m-d H:i:s'),
                            ':status' => 'Rejetée',
                            ':id' => $pending['id_demande']
                        ]);

            $this->db->commit();
            return 1;
        }
        catch (PDOException $e) 
        {
            // rollback si une transaction a été démarrée
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw new DatabaseConnectionException();
        }
    }
}