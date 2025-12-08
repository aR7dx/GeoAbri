<?php

namespace App\Controllers\API;

use App\Models\Map\InteractiveMapModel;

class EquipementsAPIController {
    public InteractiveMapModel $mapModel;

    public function __construct() {
        $this->mapModel = new InteractiveMapModel();
    }

    public function index() {
        global $router;
        
        if (isset($_GET['minLat'], $_GET['maxLat'], $_GET['minLon'], $_GET['maxLon'])) {
            $this->filteredEquipementsByPos();
            exit;
        }
        else {
            header('Location: ' . $router->generate('api'));
            exit;
        }

        return;
    }

    public function filteredEquipementsByPos() {
        $filters = [];
        $filters['minLat'] = floatval($_GET['minLat']);
        $filters['maxLat'] = floatval($_GET['maxLat']);
        $filters['minLon'] = floatval($_GET['minLon']);
        $filters['maxLon'] = floatval($_GET['maxLon']);

        if (isset($_GET['q']) && !empty($_GET['q'])) {
            $filters['query'] = htmlspecialchars($_GET['q']);
        }

        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $filters['id'] = htmlspecialchars($_GET['id']);
        }

        if (isset($_GET['category']) && !empty($_GET['category'])) {
            $filters['category'] = htmlspecialchars($_GET['category']);
        }

        $equipements = $this->mapModel->getEquipementsByFilters($filters);

        $markers = [];
        foreach ($equipements as $equipement) {
            $markers[] = [
                'id' => $equipement['id'] ?? null,
                'lat' => ((float)$equipement['latitude']) ?? null,
                'lon' => ((float)$equipement['longitude']) ?? null,
            ];
        }

        echo json_encode($markers, JSON_UNESCAPED_UNICODE);
    }
}