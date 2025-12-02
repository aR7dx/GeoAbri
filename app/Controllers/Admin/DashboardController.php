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
        PermissionMiddleware::handle("view_all_stats");
        
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

    public function users() {
        global $router;

        AuthMiddleware::handle();
        PermissionMiddleware::handle("access_dashboard");
        PermissionMiddleware::handle("view_all_account");
        
        $titre = "Gestion des utilisateurs - GeoAbri";
        
        $raw = file_get_contents("https://nominatim.openstreetmap.org/search?format=json&q=Caen");
        echo "<pre>";
        var_dump($raw);
        echo "</pre>";

        require dirname(__DIR__) . '/../Views/Admin/users.php';
    }

    public function pending() {
        global $router;

        AuthMiddleware::handle();
        PermissionMiddleware::handle("access_dashboard");
        PermissionMiddleware::handle("accept_deny_request");
        
        $titre = "Demandes en attentes - GeoAbri";

        require dirname(__DIR__) . '/../Views/Admin/pending.php';
    }

    public function alerts() {
        global $router;

        AuthMiddleware::handle();
        PermissionMiddleware::handle("access_dashboard");
        
        $titre = "Alertes - GeoAbri";

        require dirname(__DIR__) . '/../Views/Admin/alerts.php';
    }
}