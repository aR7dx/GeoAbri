<?php

namespace App\Controllers\Admin;

use App\Middlewares\AuthMiddleware;
use App\Middlewares\PermissionMiddleware;

class DashboardController {

    public function index() {
        global $router;

        AuthMiddleware::handle();
        PermissionMiddleware::handle("access_dashboard");
        
        $titre = "Dashboard - GeoAbri";

        echo "Dashboard en développement";
        
        /*
        echo "<pre>";
        var_dump($_SESSION['user']);
        echo "</pre>";
        */

        //require dirname(__DIR__) . '/../Views/Admin/dashboard.php';
    }
}