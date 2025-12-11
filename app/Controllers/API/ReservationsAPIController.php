<?php

namespace App\Controllers\API;

use App\Config\Database;
use App\Models\Map\Reservation;

class ReservationsAPIController {
    private $db;
    private $reservationModel;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->reservationModel = new Reservation($this->db);
    }

    /**
     * Créer une nouvelle réservation
     */
    public function createReservation() {
        header('Content-Type: application/json');

        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user']['connected']) || !$_SESSION['user']['connected']) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Vous devez être connecté pour réserver']);
            return;
        }

        // Récupérer les données JSON
        $data = json_decode(file_get_contents('php://input'), true);

        // Validation des données
        if (!isset($data['installation_numero']) || !isset($data['date_debut']) || !isset($data['date_fin'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Données manquantes']);
            return;
        }

        $installation_numero = $data['installation_numero'];
        $date_debut = $data['date_debut'];
        $date_fin = $data['date_fin'];
        $user_id = $_SESSION['user']['id'];

        // Valider que la date de fin est après la date de début
        if (strtotime($date_fin) < strtotime($date_debut)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'La date de fin doit être après la date de début']);
            return;
        }

        // Valider que les dates ne sont pas dans le passé
        if (strtotime($date_debut) < strtotime(date('Y-m-d'))) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Les dates ne peuvent pas être dans le passé']);
            return;
        }

        // Vérifier si l'équipement est déjà réservé pour cette période
        if ($this->reservationModel->isEquipementReserved($installation_numero, $date_debut, $date_fin)) {
            http_response_code(409);
            echo json_encode(['success' => false, 'message' => 'Cet équipement est déjà réservé pour cette période']);
            return;
        }

        // Créer la réservation
        $result = $this->reservationModel->createReservation($installation_numero, $user_id, $date_debut, $date_fin);

        if ($result) {
            http_response_code(201);
            echo json_encode(['success' => true, 'message' => 'Réservation créée avec succès']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la création de la réservation']);
        }
    }

    /**
     * Vérifier la disponibilité d'un équipement
     */
    public function checkAvailability() {
        header('Content-Type: application/json');

        $installation_numero = $_GET['installation_numero'] ?? null;
        $date_debut = $_GET['date_debut'] ?? null;
        $date_fin = $_GET['date_fin'] ?? null;

        if (!$installation_numero || !$date_debut || !$date_fin) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Paramètres manquants']);
            return;
        }

        $isReserved = $this->reservationModel->isEquipementReserved($installation_numero, $date_debut, $date_fin);

        echo json_encode([
            'success' => true,
            'available' => !$isReserved,
            'message' => $isReserved ? 'Équipement déjà réservé' : 'Équipement disponible'
        ]);
    }

    /**
     * Récupérer les réservations d'un équipement
     */
    public function getEquipementReservations() {
        header('Content-Type: application/json');

        $installation_numero = $_GET['installation_numero'] ?? null;

        if (!$installation_numero) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Numéro d\'installation manquant']);
            return;
        }

        $reservations = $this->reservationModel->getReservationsByEquipement($installation_numero);

        echo json_encode([
            'success' => true,
            'reservations' => $reservations
        ]);
    }

    /**
     * Vérifier si un équipement est actuellement réservé
     */
    public function isCurrentlyReserved() {
        header('Content-Type: application/json');

        $installation_numero = $_GET['installation_numero'] ?? null;

        if (!$installation_numero) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Numéro d\'installation manquant']);
            return;
        }

        $isReserved = $this->reservationModel->isCurrentlyReserved($installation_numero);

        echo json_encode([
            'success' => true,
            'is_reserved' => $isReserved
        ]);
    }

    /**
     * Récupérer les réservations de l'utilisateur connecté
     */
    public function getUserReservations() {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user']['connected']) || !$_SESSION['user']['connected']) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Non connecté']);
            return;
        }

        $user_id = $_SESSION['user']['id'];
        $reservations = $this->reservationModel->getReservationsByUser($user_id);

        echo json_encode([
            'success' => true,
            'reservations' => $reservations
        ]);
    }

    /**
     * Annuler une réservation
     */
    public function cancelReservation() {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user']['connected']) || !$_SESSION['user']['connected']) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Non connecté']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $reservation_id = $data['reservation_id'] ?? null;

        if (!$reservation_id) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID de réservation manquant']);
            return;
        }

        $user_id = $_SESSION['user']['id'];
        $result = $this->reservationModel->cancelReservation($reservation_id, $user_id);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Réservation annulée']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'annulation']);
        }
    }
}
