<?php

namespace App\Controllers;

class HomeController {
    public function index() {
        global $router;
        require '../views/home.php';
    }
}