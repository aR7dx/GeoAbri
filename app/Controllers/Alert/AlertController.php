<?php
namespace App\Controllers\Alert;

use App\Middlewares\AuthMiddleware;
use App\Middlewares\PermissionMiddleware;
use App\Models\Alert\Alert;

class AlertController {

    public function index() {
        global $router;

        AuthMiddleware::handle();
        PermissionMiddleware::handle("access_dashboard");
        PermissionMiddleware::handle("create_alert");

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $titre = "Gestion des alertes - GeoAbri";

        $alertModel = new Alert();
        $alerts = null;

        if (in_array('view_all_alerts', $_SESSION['user']['permissions'])) {
            $alerts = $alertModel->getAllAlerts();
        } else {
            $alerts = $alertModel->getAlertsByUser($_SESSION['user']['id']);
        }

        require dirname(__DIR__) . '/../Views/Admin/alert.php';
    }

    
    /**
     * Afficher le formulaire de création d'alerte (pour collectivités)
     */
    public function create() {
        AuthMiddleware::handle();
        PermissionMiddleware::handle("create_alert");
        
        global $router;
        
        // Démarrage de la session si nécessaire
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $titre = "Créer une alerte - GeoAbri";
        
        require dirname(__DIR__) . '/../Views/Admin/alert.php';
    }

    /**
     * Traiter la soumission du formulaire de création d'alerte
     */
    public function store() {
        AuthMiddleware::handle();
        PermissionMiddleware::handle("create_alert");
        
        global $router;
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $titre = "Créer une alerte - GeoAbri";
        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $router->generate('admin_alerts'));
            exit;
        }

        try {
            // Récupération et validation des données
            $nom = trim($_POST['nom'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $dateDebut = trim($_POST['date_debut'] ?? '');
            $dateFin = !empty($_POST['date_fin']) ? trim($_POST['date_fin']) : null;
            $niveau = intval($_POST['niveau'] ?? 1);
            $ville = trim($_POST['ville'] ?? '');
            $lat = floatval($_POST['lat'] ?? 0);
            $lon = floatval($_POST['lon'] ?? 0);

            // Validations
            if (empty($nom) || empty($description) || empty($dateDebut) || empty($ville)) {
                $error = "Tous les champs obligatoires doivent être remplis.";
                require dirname(__DIR__) . '/../Views/Alert/create.php';
                return;
            }

            if ($niveau < 1 || $niveau > 3) {
                $error = "Le niveau d'alerte doit être compris entre 1 et 3.";
                require dirname(__DIR__) . '/../Views/Alert/create.php';
                return;
            }

            if ($lat < -90 || $lat > 90 || $lon < -180 || $lon > 180) {
                $error = "Les coordonnées GPS ne sont pas valides.";
                require dirname(__DIR__) . '/../Views/Alert/create.php';
                return;
            }

            // Vérification que la date de début n'est pas dans le passé
            if (strtotime($dateDebut) < strtotime(date('Y-m-d'))) {
                $error = "La date de début ne peut pas être dans le passé.";
                require dirname(__DIR__) . '/../Views/Alert/create.php';
                return;
            }

            // Vérification que la date de fin est après la date de début
            if ($dateFin && strtotime($dateFin) < strtotime($dateDebut)) {
                $error = "La date de fin doit être postérieure à la date de début.";
                require dirname(__DIR__) . '/../Views/Alert/create.php';
                return;
            }

            $alertModel = new Alert();
            $userId = $_SESSION['user']['user_id'];

            $alertId = $alertModel->createAlert(
                $nom,
                $description,
                $dateDebut,
                $dateFin,
                $niveau,
                $userId,
                $ville,
                $lat,
                $lon
            );

            if ($alertId) {
                $_SESSION['alert_success'] = "L'alerte a été créée avec succès !";
                header('Location: ' . $router->generate('admin_alerts'));
                exit;
            } else {
                $error = "Une erreur est survenue lors de la création de l'alerte.";
            }

        } catch (\Exception $e) {
            $error = "Une erreur est survenue : " . $e->getMessage();
        }

        require dirname(__DIR__) . '/../Views/Admin/alert.php';
    }


    /**
     * Supprimer une alerte
     */
    public function delete() {
        AuthMiddleware::handle();
        
        global $router;
        
        if (!isset($_GET['id'])) {
            header('Location: ' . $router->generate('admin_alerts'));
            exit;
        }

        $alertId = intval($_GET['id']);
        $alertModel = new Alert();
        
        // Vérifier que l'alerte appartient à l'utilisateur
        $alert = $alertModel->getAlertById($alertId);
        
        if (!$alert) {
            $_SESSION['alert_error'] = "Alerte introuvable.";
            header('Location: ' . $router->generate('admin_alerts'));
            exit;
        }

        // Vérifier les permissions
        if ($alert['propietaire_id'] != $_SESSION['user']['user_id'] && 
            !in_array('delete_all_alerts', $_SESSION['user']['permissions'] ?? [])) {
            $_SESSION['alert_error'] = "Vous n'avez pas la permission de supprimer cette alerte.";
            header('Location: ' . $router->generate('admin_alerts'));
            exit;
        }

        try {
            if ($alertModel->deleteAlert($alertId)) {
                $_SESSION['alert_success'] = "L'alerte a été supprimée avec succès.";
            } else {
                $_SESSION['alert_error'] = "Impossible de supprimer l'alerte.";
            }
        } catch (\Exception $e) {
            $_SESSION['alert_error'] = "Une erreur est survenue.";
        }

        header('Location: ' . $router->generate('admin_alerts'));
        exit;
    }
}