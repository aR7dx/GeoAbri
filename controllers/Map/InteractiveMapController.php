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
        $equipement = $id !== null ? $this->mapModel->getEquipement($id)[0] : [];

        if ($equipement['website'] !== null && $this->urlNotContainHttpOrHttps($equipement['website'])) {
            $equipement['website'] = "https://" . $equipement['website'];

            // TODO faire appel à une nouvelle méthode dans la base ou le model qui à une requete update 
            // qui corrige l'url en se servant de l'id $equipement['id']
        }

        // reception du fetch js
        if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
            $this->transmitFilteredEquipements();
            return;
        }

        require '../views/Map/interactive-map.php';
    }

    /**
     *  Cette fonction sera appelée uniquement dans la page appellée par le fetch en js
     * */
    public function transmitFilteredEquipements() {
        $filters = [];
        if (isset($_GET['minLat'], $_GET['maxLat'], $_GET['minLon'], $_GET['maxLon'])) {
            $filters['minLat'] = floatval($_GET['minLat']);
            $filters['maxLat'] = floatval($_GET['maxLat']);
            $filters['minLon'] = floatval($_GET['minLon']);
            $filters['maxLon'] = floatval($_GET['maxLon']);
        }

        if (!empty($_GET['q'])) {
            $filters['query'] = $_GET['q'];
        }

        $equipements = $this->mapModel->getEquipementsByFilters($filters);

        $markers = [];
        foreach ($equipements as $equipement) {
            if ($equipement['website'] !== null && $this->urlNotContainHttpOrHttps($equipement['website'])) {
                $equipement['website'] = "https://" . $equipement['website'];
            }

            $markers[] = [
                'id' => $equipement['id'] ?? null,
                'name' => $equipement['name'] ?? null,
                'latitude' => ((float)$equipement['latitude']) ?? null,
                'longitude' => ((float)$equipement['longitude']) ?? null,
                'activites' => $equipement['activites'] ?? null,
                'website' => $equipement['website'] ?? null,
            ];
        }

        echo json_encode($markers, JSON_UNESCAPED_UNICODE);
    }


    /**
     * Retourne un boolean pour savoir si l'url comporte ou non les chaines "http" et "https"
     */
    function urlNotContainHttpOrHttps(string $url): bool {
        return !empty($url) && !str_contains($url, 'http') && !str_contains($url, 'https');
    }
}