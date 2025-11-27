<?php

namespace App\Controllers\API;

class APIController {
    public function index() {
        global $router;
        $titre = "API - GeoAbri";

        require dirname(__DIR__) . '/../Views/API/api.php';
    }
}