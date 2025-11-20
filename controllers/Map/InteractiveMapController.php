<?php

namespace App\Controllers\Map;

use App\Models\Admin\DataStatsModel;
use App\Models\Map\InteractiveMapModel;

class InteractiveMapController {
    public InteractiveMapModel $mapModel;
    public DataStatsModel $dataStatsModel;

    public function __construct() {
        $this->mapModel = new InteractiveMapModel();
        $this->dataStatsModel = new DataStatsModel();
    }

    public function index() {
        global $router;
        $titre = "Carte Interactive - Équipements d'urgences";
        
        $query = (isset($_GET['q']) && !empty($_GET['q'])) ? strtolower($_GET['q']) : null;
        $id = (isset($_GET['id']) && !empty($_GET['id'])) ? $_GET['id'] : null;
        $suggestions = $query !== null ? $this->mapModel->getSearchSuggestions($query) : [];
        //$nbEquipementsTotal = $this->dataStatsModel->getTotalEquipementsCount();

        $equipement = $id !== null ? $this->mapModel->getEquipement($id)[0] : [];
        //$activities = $this->mapModel->getActivities();

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

        $equipements = $this->mapModel->getEquipementsByFilters($filters);

        $markers = [];
        foreach ($equipements as $equipement) {
            $markers[] = [
                'id' => $equipement['id'] ?? null,
                'name' => $equipement['name'] ?? null,
                'latitude' => ((float)$equipement['latitude']) ?? null,
                'longitude' => ((float)$equipement['longitude']) ?? null,
                'activites' => $equipement['activites'] ?? null,
            ];
        }

        echo json_encode($markers, JSON_UNESCAPED_UNICODE);
    }
}