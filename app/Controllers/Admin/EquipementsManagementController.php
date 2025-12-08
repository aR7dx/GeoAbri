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

    public function handler() {
        global $router;

        PermissionMiddleware::handle("access_dashboard");
        PermissionMiddleware::handle("edit_equipement");

        if (isset($_POST['add']) && $_POST['add'] === "1") {
            $this->add($_POST);
        }

        elseif (isset($_POST['edit']) && $_POST['edit'] === "1") {
            $this->edit($_POST);
        }

        elseif (isset($_POST['delete']) && $_POST['delete'] === "1") {
            $this->delete($_POST);
        }

        exit;
    }

    public function add($post) {
        global $router;

        AuthMiddleware::handle();
        PermissionMiddleware::handle("access_dashboard");
        PermissionMiddleware::handle("edit_equipement");
        
        $equipementModel = new Equipement();
       
        if($equipementModel->add($post) !== true) {
            echo "Probleme lors de l'insertion";
            // TODO
            // Il faudrait plutot ramener l'utilisateur à la page equipement du dashboard et dire 
            // qu'il y a eu un problème lors de l'insertion
        }
        else {
            header('Location: ' . $router->generate('admin_equipements'));
        }
        exit;
    }

    public function edit($post) {
        global $router;

        AuthMiddleware::handle();
        PermissionMiddleware::handle("access_dashboard");
        PermissionMiddleware::handle("edit_equipement");

        $equipementModel = new Equipement();

        if($equipementModel->edit($post) !== true) {
            echo "Probleme lors de la modification";
            // TODO
            // Il faudrait plutot ramener l'utilisateur à la page equipement du dashboard et dire 
            // qu'il y a eu un problème lors de l'insertion
        }
        else {
            header('Location: ' . $router->generate('admin_equipements'));
        }
        exit;
    }

    public function delete($post) {
        global $router;

        AuthMiddleware::handle();
        PermissionMiddleware::handle("access_dashboard");
        PermissionMiddleware::handle("edit_equipement");

        $equipementModel = new Equipement();

        if($equipementModel->delete($post) !== true) {

            $_SESSION['notification']['not_your_equipement'] = 1;
            header('Location: ' . $router->generate('admin_equipements'));
            exit;
        }
            
        header('Location: ' . $router->generate('admin_equipements'));
        exit;
    }

}