<?php

namespace App\Controllers\Map;

class MapViewController {
    public function index() {
        global $router;
        require '../views/map/view.php';
    }
}