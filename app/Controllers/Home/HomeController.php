<?php

namespace App\Controllers\Home;

class HomeController {
    public function index() {
        global $router;
        $titre = "Accueil - Équipements d'urgences";

        require dirname(dirname(__DIR__)) . '/Views/Home/home.php';
    }
}