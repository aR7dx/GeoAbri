<?php

namespace App\Controllers\Admin;

use App\Middlewares\AuthMiddleware;
use App\Middlewares\PermissionMiddleware;
use App\Models\Admin\Pending;

class PendingsManagementController {
    public function index() {
        global $router;

        AuthMiddleware::handle();
        PermissionMiddleware::handle("access_dashboard");
        PermissionMiddleware::handle("accept_deny_request");
        
        $titre = "Demandes en attentes - GeoAbri";

        $pendingModel = new Pending();
        $pendings = $pendingModel->getActivePendings() ?? null;

        if (!empty($pendings) && !is_array(reset($pendings))) {
            $pendings = [$pendings];
        }
        
        require dirname(__DIR__) . '/../Views/Admin/pending.php';
    }

    public function handler() {
        global $router;

        AuthMiddleware::handle();
        PermissionMiddleware::handle("access_dashboard");
        PermissionMiddleware::handle("accept_deny_request");

        $pendingModel = new Pending();

        if(isset($_POST['accept']) && $_POST['accept'] === "1") {

            if ($pendingModel->accept(base64_decode($_POST['id'])) !== 1) {
                // TODO 
                // modifier une variable session pour afficher une notif pour dire que cela n'a pas fonctionné
            }

            header('Location: ' . $router->generate('admin_pendings'));
            exit;
        }

        if(isset($_POST['deny']) && $_POST['deny'] === "1") {

            if ($pendingModel->deny(base64_decode($_POST['id'])) !== 1) {
                // TODO 
                // modifier une variable session pour afficher une notif pour dire que cela n'a pas fonctionné
            }

            header('Location: ' . $router->generate('admin_pendings'));
            exit;
        }
        
        exit;
    }

}