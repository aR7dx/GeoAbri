<?php

namespace App\Controllers\Map;

use App\Models\Map\InteractiveMapModel;

class InteractiveMapController {
    public InteractiveMapModel $mapModel;

    public function __construct() {
        $this->mapModel = new InteractiveMapModel();
    }

    public function index() {
        global $router;
        $titre = "Carte Interactive - Équipements d'urgences";
        
        $query = (isset($_GET['q']) && !empty($_GET['q'])) ? strtolower($_GET['q']) : null;
        $id = (isset($_GET['id']) && !empty($_GET['id'])) ? $_GET['id'] : null;
        $suggestions = $query !== null ? $this->mapModel->getSearchSuggestions($query) : [];
        $equipement = $id !== null ? $this->mapModel->getEquipement($id) : [];
        $equipement = !empty($equipement) ? $equipement[0] : [];

        if (!empty($equipement['website']) && $this->urlNotContainHttpOrHttps($equipement['website'])) {
            $equipement['website'] = "https://" . $equipement['website'];
        }

        require '../views/Map/interactive-map.php';
    }

    /**
     * Retourne un boolean pour savoir si l'url comporte ou non les chaines "http" et "https"
     */
    function urlNotContainHttpOrHttps(string $url): bool {
        return !empty($url) && !str_contains($url, 'http') && !str_contains($url, 'https');

        // TODO faire appel à une nouvelle méthode dans la base ou un nouveau model qui à une requete update 
        // qui corrige l'url en se servant de l'id $equipement['id']
    }
}