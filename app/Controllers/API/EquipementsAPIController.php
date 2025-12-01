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
            header('Location: /api');
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
                'lat' => ((float)$equipement['latitude']) ?? null,
                'lon' => ((float)$equipement['longitude']) ?? null,
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