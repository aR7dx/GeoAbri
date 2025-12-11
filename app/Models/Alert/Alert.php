<?php
namespace App\Models\Alert;

use PDO;
use PDOException;
use App\Config\Database;
use App\Exceptions\Database\DatabaseConnectionException;

class Alert {
    
    private PDO $db;

    public function __construct() {
        try {
            $this->db = Database::getInstance()->getConnection();
        } catch (PDOException $e) {
            throw new DatabaseConnectionException();
        }
    }

    /**
     * Créer une nouvelle alerte
     */
    public function createAlert(string $nom, string $description, string $dateDebut, ?string $dateFin, int $niveau, int $proprietaireId, string $ville, float $lat, float $lon) {
        try {
            $sql = "INSERT INTO GEO_ALERTES (nom, description, date_debut, date_fin, niveau, propietaire_id, ville, lat, lon) 
                    VALUES (:nom, :description, :date_debut, :date_fin, :niveau, :propietaire_id, :ville, :lat, :lon)";
            
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([
                ':nom' => $nom,
                ':description' => $description,
                ':date_debut' => $dateDebut,
                ':date_fin' => $dateFin,
                ':niveau' => $niveau,
                ':propietaire_id' => $proprietaireId,
                ':ville' => $ville,
                ':lat' => $lat,
                ':lon' => $lon
            ]);
            
            return $result ? $this->db->lastInsertId() : false;
        } catch (\PDOException $e) {
            throw new DatabaseConnectionException();
        }
    }

    /**
     * Récupérer toutes les alertes
     */
    public function getAllAlerts() {
        try {
            $sql = "SELECT a.*, u.nom as user_nom, u.prenom as user_prenom, u.email as user_email
                    FROM GEO_ALERTES a
                    LEFT JOIN GEO_UTILISATEURS u ON a.propietaire_id = u.user_id
                    ORDER BY a.date_debut DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new DatabaseConnectionException();
        }
    }

    /**
     * Récupérer les alertes actives (date_fin NULL ou > aujourd'hui)
     */
    public function getActiveAlerts() {
        try {
            $sql = "SELECT a.*, u.nom as user_nom, u.prenom as user_prenom, u.email as user_email
                    FROM GEO_ALERTES a
                    LEFT JOIN GEO_UTILISATEURS u ON a.propietaire_id = u.user_id
                    WHERE a.date_fin IS NULL OR a.date_fin >= CURDATE()
                    ORDER BY a.niveau DESC, a.date_debut DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new DatabaseConnectionException();
        }
    }

    /**
     * Récupérer les alertes d'un utilisateur spécifique
     */
    public function getAlertsByUser(int $userId) {
        try {
            $sql = "SELECT a.*, u.nom as user_nom, u.prenom as user_prenom, u.email as user_email
                    FROM GEO_ALERTES a
                    LEFT JOIN GEO_UTILISATEURS u ON a.propietaire_id = u.user_id
                    WHERE a.propietaire_id = :user_id
                    ORDER BY a.date_debut DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':user_id' => $userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new DatabaseConnectionException();
        }
    }

    /**
     * Récupérer une alerte par son ID
     */
    public function getAlertById(int $alertId) {
        try {
            $sql = "SELECT a.*, u.nom as user_nom, u.prenom as user_prenom, u.email as user_email
                    FROM GEO_ALERTES a
                    LEFT JOIN GEO_UTILISATEURS u ON a.propietaire_id = u.user_id
                    WHERE a.id_alerte = :id_alerte";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id_alerte' => $alertId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new DatabaseConnectionException();
        }
    }

    /**
     * Mettre à jour une alerte
     */
    public function updateAlert(int $alertId, string $nom, string $description, string $dateDebut, ?string $dateFin, int $niveau, string $ville, float $lat, float $lon) {
        try {
            $sql = "UPDATE GEO_ALERTES 
                    SET nom = :nom, 
                        description = :description, 
                        date_debut = :date_debut, 
                        date_fin = :date_fin, 
                        niveau = :niveau,
                        ville = :ville,
                        lat = :lat,
                        lon = :lon
                    WHERE id_alerte = :id_alerte";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':nom' => $nom,
                ':description' => $description,
                ':date_debut' => $dateDebut,
                ':date_fin' => $dateFin,
                ':niveau' => $niveau,
                ':ville' => $ville,
                ':lat' => $lat,
                ':lon' => $lon,
                ':id_alerte' => $alertId
            ]);
        } catch (PDOException $e) {
            throw new DatabaseConnectionException();
        }
    }

    /**
     * Supprimer une alerte
     */
    public function deleteAlert(int $alertId) {
        try {
            $sql = "DELETE FROM GEO_ALERTES WHERE id_alerte = :id_alerte";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id_alerte' => $alertId]);
        } catch (PDOException $e) {
            throw new DatabaseConnectionException();
        }
    }

    /**
     * Compter le nombre total d'alertes actives
     */
    public function countActiveAlerts() {
        try {
            $sql = "SELECT COUNT(*) as total 
                    FROM GEO_ALERTES 
                    WHERE date_fin IS NULL OR date_fin >= CURDATE()";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (PDOException $e) {
            throw new DatabaseConnectionException();
        }
    }

    /**
     * Récupérer les alertes actives dans une zone géographique
     */
    public function getActiveAlertsInBounds($latMin, $latMax, $lonMin, $lonMax) {
        try {
            $sql = "SELECT * FROM GEO_ALERTES 
                    WHERE (date_fin IS NULL OR date_fin >= CURDATE())
                    AND lat BETWEEN :lat_min AND :lat_max
                    AND lon BETWEEN :lon_min AND :lon_max
                    ORDER BY niveau DESC, date_debut DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':lat_min' => $latMin,
                ':lat_max' => $latMax,
                ':lon_min' => $lonMin,
                ':lon_max' => $lonMax
            ]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new DatabaseConnectionException();
        }
    }
}