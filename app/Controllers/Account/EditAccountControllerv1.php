<?php

namespace App\Controllers\Account;

use App\Middlewares\AuthMiddleware;
use App\Models\Auth\User;

class EditAccountController {

    public function index() {
        AuthMiddleware::handle();

        global $router;
        $titre = "Modifier mon compte - GeoAbri";

        require dirname(__DIR__) . '/../Views/Account/edit.php';
    }



    public function edit() {
        AuthMiddleware::handle();
        global $router;
        
       //nouvelle sesion
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $titre = "Modifier mon compte - GeoAbri";
        $error = null;
        $success = null;

        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $router->generate('edit_account'));
            exit;
        }

        try {
            //recup 
            $prenom = trim($_POST['prenom'] ?? '');
            $nom = trim($_POST['nom'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $telephone = trim($_POST['telephone'] ?? '');
            $ville = trim($_POST['ville'] ?? '');
            $codePostal = trim($_POST['code_postal'] ?? '');
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            // tests
            if (empty($prenom) || empty($nom) || empty($email) || empty($telephone) || empty($ville) || empty($codePostal)) {
                $error = "Tous les champs obligatoires doivent être remplis.";
                require dirname(__DIR__) . '/../Views/Account/edit.php';
                return;
            }

            // Validation de l'email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "L'adresse e-mail n'est pas valide.";
                require dirname(__DIR__) . '/../Views/Account/edit.php';
                return;
            }

            // Validation du téléphone (10 chiffres)
            if (!preg_match('/^[0-9]{10}$/', $telephone)) {
                $error = "Le numéro de téléphone doit contenir exactement 10 chiffres.";
                require dirname(__DIR__) . '/../Views/Account/edit.php';
                return;
            }

            // Validation du code postal (5 chiffres)
            if (!preg_match('/^[0-9]{5}$/', $codePostal)) {
                $error = "Le code postal doit contenir exactement 5 chiffres.";
                require dirname(__DIR__) . '/../Views/Account/edit.php';
                return;
            }

            $userModel = new User();
            $userId = $_SESSION['user']['id'];

            // Vérification si l'email a changé et s'il n'est pas déjà utilisé
            if ($email !== $_SESSION['user']['email']) {
                $existingUser = $userModel->findByEmail($email);
                if ($existingUser && $existingUser['user_id'] != $userId) {
                    $error = "Cette adresse e-mail est déjà utilisée par un autre compte.";
                    require dirname(__DIR__) . '/../Views/Account/edit.php';
                    return;
                }
            }

            // Gestion du changement de mot de passe
            $newPasswordHash = null;
            if (!empty($newPassword) || !empty($confirmPassword) || !empty($currentPassword)) {
                // Vérification que tous les champs de mot de passe sont remplis
                if (empty($currentPassword)) {
                    $error = "Veuillez entrer votre mot de passe actuel pour modifier votre mot de passe.";
                    require dirname(__DIR__) . '/../Views/Account/edit.php';
                    return;
                }

                if (empty($newPassword) || empty($confirmPassword)) {
                    $error = "Veuillez remplir tous les champs de mot de passe.";
                    require dirname(__DIR__) . '/../Views/Account/edit.php';
                    return;
                }

                // Vérification de la longueur du nouveau mot de passe
                if (strlen($newPassword) < 8) {
                    $error = "Le nouveau mot de passe doit contenir au moins 8 caractères.";
                    require dirname(__DIR__) . '/../Views/Account/edit.php';
                    return;
                }

                // Vérification que les nouveaux mots de passe correspondent
                if ($newPassword !== $confirmPassword) {
                    $error = "Les nouveaux mots de passe ne correspondent pas.";
                    require dirname(__DIR__) . '/../Views/Account/edit.php';
                    return;
                }

                // Récupération de l'utilisateur pour vérifier le mot de passe actuel
                $user = $userModel->findByEmail($_SESSION['user']['email']);
                
                if (!$user || !password_verify($currentPassword, $user['password_hash'])) {
                    $error = "Le mot de passe actuel est incorrect.";
                    require dirname(__DIR__) . '/../Views/Account/edit.php';
                    return;
                }

                // Vérification que le nouveau mot de passe est différent de l'ancien
                if (password_verify($newPassword, $user['password_hash'])) {
                    $error = "Le nouveau mot de passe doit être différent de l'ancien.";
                    require dirname(__DIR__) . '/../Views/Account/edit.php';
                    return;
                }

                // Hash du nouveau mot de passe
                $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
            }

            // Mise à jour de l'utilisateur
            $updated = $userModel->editUser(
                $userId,
                $nom,
                $prenom,
                $email,
                $telephone,
                $ville,
                $codePostal,
                $newPasswordHash
            );

            if ($updated) {
                // Mise à jour de la session
                $_SESSION['user']['nom'] = $nom;
                $_SESSION['user']['prenom'] = $prenom;
                $_SESSION['user']['email'] = $email;
                $_SESSION['user']['telephone'] = $telephone;
                $_SESSION['user']['ville'] = $ville;
                $_SESSION['user']['code_postal'] = $codePostal;

                $success = "Votre profil a été mis à jour avec succès !";
            } else {
                $error = "Une erreur est survenue lors de la mise à jour de votre profil.";
            }

        } catch (\Exception $e) {
            $error = "Une erreur est survenue : " . $e->getMessage();
        }

        require dirname(__DIR__) . '/../Views/Account/edit.php';
    }
}