<?php

namespace App\Controllers\Map;

class InteractiveMapController {
    public function index() {
        global $router;
        $titre = "Carte Interactive - Équipements d'urgences";
        
        require '../views/Map/interactive-map.php';
    }
}