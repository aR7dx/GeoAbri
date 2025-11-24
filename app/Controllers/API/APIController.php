<?php

namespace App\Controllers\API;

class APIController {
    public function index() {
        global $router;
        $titre = "API - Équipements d'urgences";

        require dirname(__DIR__) . '/../Views/API/api.php';
    }
}