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
        
        $equipementModel = new Equipement();
       
        if($equipementModel->add($_POST) !== true) {
            echo "Probleme lors de l'insertion";
            // TODO
            // Il faudrait plutot ramener l'utilisateur à la page equipement du dashboard et dire 
            // qu'il y a eu un problème lors de l'insertion
        }
        else {
            header('Location: ' . $router->generate('admin_equipements'));
        }
    }

}