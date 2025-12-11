<?php

namespace App\Models\Map;

use PDO;
use PDOException;

class Reservation {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Créer une nouvelle réservation
     */
    public function createReservation($installation_numero, $user_id, $date_debut, $date_fin) {
        try {
            $query = "INSERT INTO GEO_RESERVATIONS (installation_numero, user_id, date_debut, date_fin) 
                      VALUES (:installation_numero, :user_id, :date_debut, :date_fin)";
            
            $stmt = $this->db->prepare($query);
            return $stmt->execute([':installation_numero' => $installation_numero,
                            ':user_id' => $user_id,
                            ':date_debut' => $date_debut,
                            ':date_fin' => $date_fin]);
        } catch (PDOException $e) {
            error_log("Erreur lors de la création de la réservation: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Vérifier si un équipement est déjà réservé pour une période donnée
     */
    public function isEquipementReserved($installation_numero, $date_debut, $date_fin) {
        try {
            $query = "SELECT COUNT(*) as count FROM GEO_RESERVATIONS 
                      WHERE installation_numero = :installation_numero 
                      AND (
                          (date_debut <= :date_debut AND date_fin >= :date_debut) OR
                          (date_debut <= :date_fin AND date_fin >= :date_fin) OR
                          (date_debut >= :date_debut AND date_fin <= :date_fin)
                      )";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':installation_numero', $installation_numero);
            $stmt->bindParam(':date_debut', $date_debut);
            $stmt->bindParam(':date_fin', $date_fin);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'] > 0;
        } catch (PDOException $e) {
            error_log("Erreur lors de la vérification de la réservation: " . $e->getMessage());
            return true; // En cas d'erreur, on considère l'équipement comme réservé par sécurité
        }
    }

    /**
     * Récupérer toutes les réservations d'un équipement
     */
    public function getReservationsByEquipement($installation_numero) {
        try {
            $query = "SELECT r.*, u.nom as user_nom, u.prenom as user_prenom, u.email as user_email
                      FROM GEO_RESERVATIONS r
                      JOIN GEO_UTILISATEURS u ON r.user_id = u.id
                      WHERE r.installation_numero = :installation_numero
                      ORDER BY r.date_debut DESC";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':installation_numero', $installation_numero);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des réservations: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupérer les réservations d'un utilisateur
     */
    public function getReservationsByUser($user_id) {
        try {
            $query = "SELECT r.*, e.nom as equipement_nom, e.type as equipement_type, 
                             e.commune, e.coordonnees
                      FROM GEO_RESERVATIONS r
                      JOIN GEO_EQUIPEMENT e ON r.installation_numero = e.installation_numero
                      WHERE r.user_id = :user_id
                      ORDER BY r.date_debut DESC";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des réservations de l'utilisateur: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Annuler une réservation
     */
    public function cancelReservation($reservation_id, $user_id) {
        try {
            $query = "DELETE FROM GEO_RESERVATIONS 
                      WHERE id = :id 
                      AND user_id = :user_id";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $reservation_id);
            $stmt->bindParam(':user_id', $user_id);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de l'annulation de la réservation: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Vérifier si un équipement est réservé actuellement (aujourd'hui)
     */
    public function isCurrentlyReserved($installation_numero) {
        try {
            $today = date('Y-m-d');
            $query = "SELECT COUNT(*) as count FROM GEO_RESERVATIONS 
                      WHERE installation_numero = :installation_numero 
                      AND date_debut <= :today 
                      AND date_fin >= :today";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':installation_numero', $installation_numero);
            $stmt->bindParam(':today', $today);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'] > 0;
        } catch (PDOException $e) {
            error_log("Erreur lors de la vérification de la réservation actuelle: " . $e->getMessage());
            return false;
        }
    }
}
