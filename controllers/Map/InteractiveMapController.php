<?php

namespace App\Controllers\Map;

use App\Models\Map\InteractiveMapModel;

class InteractiveMapController {
    public InteractiveMapModel $model;

    public function __construct() {
        $this->model = new InteractiveMapModel();
    }

    public function index() {
        global $router;
        $titre = "Carte Interactive - Équipements d'urgences";
        
        $query = isset($_GET['q']) ? strtolower($_GET['q']) : null;
        $id = $_GET['id'] ?? 'NULL';
        $suggestions = $query !== null ? $this->model->getSearchSuggestions($query) : [];
        $equipement = $this->model->getEquipement($id);
        //$activities = $this->model->getActivities();

        if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
            $this->handleAjaxRequest();
            return;
        }

        require '../views/Map/interactive-map.php';
    }

    public function handleAjaxRequest() {
        $filters = [];
        if (isset($_GET['minLat'], $_GET['maxLat'], $_GET['minLon'], $_GET['maxLon'])) {
            $filters['minLat'] = floatval($_GET['minLat']);
            $filters['maxLat'] = floatval($_GET['maxLat']);
            $filters['minLon'] = floatval($_GET['minLon']);
            $filters['maxLon'] = floatval($_GET['maxLon']);
        }

        if (!empty($_GET['activite'])) {
            $filters['activites'] = $_GET['activite'];
        }

        if (!empty($_GET['q'])) {
            $filters['query'] = $_GET['q'];
        }

        $equipements = $this->model->getEquipementsByFilters($filters);

        $markers = [];
        foreach ($equipements as $equipement) {
            $markers[] = [
                'name' => $equipement['name'] ?? null,
                'latitude' => ((float)$equipement['latitude']) ?? null,
                'longitude' => ((float)$equipement['longitude']) ?? null,
                'activites' => $equipement['activites'] ?? null,
            ];
        }

        echo json_encode($markers, JSON_UNESCAPED_UNICODE);
    }
}