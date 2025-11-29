<?php

namespace App\Controllers\Admin;

use App\Middlewares\AuthMiddleware;
use App\Middlewares\PermissionMiddleware;
use App\Models\Admin\DashboardStats;

class DashboardController {
    public function index() {
        global $router;

        AuthMiddleware::handle();
        PermissionMiddleware::handle("access_dashboard");
        
        $titre = "Dashboard - GeoAbri";

        $statsModel = new DashboardStats();

        $totalEquipements = $statsModel->getTotalEquipements();
        $totalUsers = $statsModel->getTotalUsers();
        $totalDemands = $statsModel->getTotalDemands();
        $totalAlerts = $statsModel->getTotalAlerts();

        $rolesData = json_encode($statsModel->getRolesRepartition());

        $rawEvolution = $statsModel->getEquipementEvolution();

        $cumulative = [];
        $total = 0;

        foreach ($rawEvolution as $row) {
            $total += (int)$row['nouveaux'];
            $cumulative[] = [
                'annee' => (int)$row['annee'],
                'total' => $total
            ];
        }

        $equipementsEvolution = json_encode($cumulative);

        require dirname(__DIR__) . '/../Views/Admin/dashboard.php';
    }

    public function equipements() {
        global $router;

        AuthMiddleware::handle();
        PermissionMiddleware::handle("access_dashboard");
        
        $titre = "Gestion des équipements - GeoAbri";

        require dirname(__DIR__) . '/../Views/Admin/equipement-management.php';
    }

    public function users() {
        global $router;

        AuthMiddleware::handle();
        PermissionMiddleware::handle("access_dashboard");
        
        $titre = "Gestion des utilisateurs - GeoAbri";

        require dirname(__DIR__) . '/../Views/Admin/user-management.php';
    }

    public function pending() {
        global $router;

        AuthMiddleware::handle();
        PermissionMiddleware::handle("access_dashboard");
        
        $titre = "Demandes en attentes - GeoAbri";

        require dirname(__DIR__) . '/../Views/Admin/pending-request.php';
    }
}