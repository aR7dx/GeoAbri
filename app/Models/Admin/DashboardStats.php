<?php

namespace App\Models\Admin;

use PDO;
use App\Config\Database;
use App\Exceptions\Database\DatabaseConnectionException;


class DashboardStats {

    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getTotalEquipements() 
    {
        try{
            $sql = "SELECT COUNT(*) as total FROM GEO_EQUIPEMENT";

            /*
            $stmt = null;
            if (isset($_SESSION['user']) && !in_array('view_all_stats', $_SESSION['user']['permissions'])) {
                $sql .= " join GEO_APPARTENIR using(installation_numero) where user_id = :uid";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([':uid' => $_SESSION['user']['id']]);
            } else {
                $stmt = $this->db->prepare($sql);
                $stmt->execute();
            }
            */

            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $donnees = $stmt->fetchAll(PDO::FETCH_COLUMN);
            return $donnees[0];
        }
        catch (PDOException $e) 
        {
            throw new DatabaseConnectionException();
        }
        return 0;
    }

    public function getTotalUsers() 
    {
        try{
            $sql = "SELECT COUNT(*) as total FROM GEO_UTILISATEURS";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $donnees = $stmt->fetchAll(PDO::FETCH_COLUMN);
            return $donnees[0];
        }
        catch (PDOException $e) 
        {
            throw new DatabaseConnectionException();
        }
        return 0;
    }

    public function getTotalDemands() 
    {
        try{
            $sql = "SELECT COUNT(*) as total FROM GEO_DEMANDES";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $donnees = $stmt->fetchAll(PDO::FETCH_COLUMN);
            return $donnees[0];
        }
        catch (PDOException $e) 
        {
            throw new DatabaseConnectionException();
        }
        return 0;
    }

    public function getTotalAlerts() 
    {
        try{
            $sql = "SELECT COUNT(*) as total FROM GEO_ALERTES";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $donnees = $stmt->fetchAll(PDO::FETCH_COLUMN);
            return $donnees[0];
        }
        catch (PDOException $e) 
        {
            throw new DatabaseConnectionException();
        }
        return 0;
    }

    public function getRolesRepartition()
    {
        $sql = "SELECT r.name AS role, COUNT(*) AS total
            FROM GEO_UTILISATEURS u
            LEFT JOIN GEO_ROLES r ON u.role_id = r.role_id
            GROUP BY r.name";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEquipementEvolution()
    {
        $sql = "SELECT coalesce(mise_en_service_date, 2025) AS annee, COUNT(*) AS nouveaux 
            FROM GEO_EQUIPEMENT
            WHERE creation_dt IS NOT NULL 
            GROUP BY mise_en_service_date 
            ORDER BY annee";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}