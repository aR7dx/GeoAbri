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
        
        $query = (isset($_GET['q']) && !empty($_GET['q'])) ? strtolower($_GET['q']) : null;
        //$id = (isset($_GET['id']) && !empty($_GET['id'])) ? $_GET['id'] : null;
        
        if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
            $this->transmitFilteredEquipements();
            exit;
        }
        else {
            header('Location: /');
            exit;
        }

        return;
    }

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
             if (!empty($equipement['website']) && $this->urlNotContainHttpOrHttps($equipement['website'])) {
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

        // TODO faire appel à une nouvelle méthode dans la base ou le model qui à une requete update 
        // qui corrige l'url en se servant de l'id $equipement['id']
    }
}