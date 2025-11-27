<?php

namespace App\Controllers\Home;

class HomeController {
    public function index() {
        global $router;
        $titre = "Accueil - GeoAbri";

        require dirname(dirname(__DIR__)) . '/Views/Home/home.php';
    }
}