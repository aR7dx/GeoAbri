<?php

namespace App\Controllers\API;

use App\Models\Alert\Alert;

class AlertsAPIController {
    
    /**
     * Récupérer les alertes actives dans une zone géographique
     */
    public function getActiveAlertsInBounds() {
        header('Content-Type: application/json');
        
        // Récupérer les paramètres de la zone
        $latMin = $_GET['lat_min'] ?? null;
        $latMax = $_GET['lat_max'] ?? null;
        $lonMin = $_GET['lon_min'] ?? null;
        $lonMax = $_GET['lon_max'] ?? null;
        
        if (!$latMin || !$latMax || !$lonMin || !$lonMax) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Paramètres manquants']);
            return;
        }
        
        try {
            $alertModel = new Alert();
            $alerts = $alertModel->getActiveAlertsInBounds($latMin, $latMax, $lonMin, $lonMax);
            
            echo json_encode([
                'success' => true,
                'alerts' => $alerts,
                'count' => count($alerts)
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erreur serveur']);
        }
    }
}
