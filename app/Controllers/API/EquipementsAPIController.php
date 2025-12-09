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

        $equipements = $this->mapModel->getEquipementsByFilters($_GET, 3000);

        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: public, max-age=60');
        echo json_encode([
            "count" => count($equipements),
            "equipements" => $equipements
        ], JSON_NUMERIC_CHECK);
    }
}