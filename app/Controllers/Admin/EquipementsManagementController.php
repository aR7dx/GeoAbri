<?php

namespace App\Controllers\Admin;

use App\Middlewares\AuthMiddleware;
use App\Middlewares\PermissionMiddleware;
use App\Models\Map\Equipement;

class EquipementsManagementController {
    public function index() {
        global $router;

        AuthMiddleware::handle();
        PermissionMiddleware::handle("access_dashboard");
        PermissionMiddleware::handle("edit_equipement");
        
        $titre = "Gestion des équipements - GeoAbri";

        require dirname(__DIR__) . '/../Views/Admin/equipements.php';
    }

    public function add() {
        global $router;

        AuthMiddleware::handle();
        PermissionMiddleware::handle("access_dashboard");
        PermissionMiddleware::handle("edit_equipement");

        if (!isset($_POST['name'], $_POST['commune'], $_POST['code_postal'], $_POST['adresse'], $_POST['description'])) {
            echo 'probleme dans le formulaire';
        }
        
       $equipementModel = new Equipement();
       var_dump($equipementModel->add($_POST));

        echo '<pre>';
        var_dump($_POST);
        echo '</pre>';
    }

}