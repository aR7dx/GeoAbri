<!DOCTYPE html>
<html lang="fr">

<?php require_once dirname(dirname(__DIR__)) . '/Views/Includes/meta.php'; ?>

<body>

    <?php require_once __DIR__ . './../Components/navbar.php'; ?>

    <div class="container d-flex flex-column align-items-center justify-content-center pt-5 mb-5 gap-5">
        <h1 class="text-center m-0">Votre profil</h1>
        
        <div class="card shadow-sm p-4 mx-auto" style="max-width: 700px;">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex flex-row justify-content-center align-items-center">
                    <div class="rounded-circle bg-danger text-white d-flex justify-content-center align-items-center" style="user-select: none; width:70px; height:70px; min-width: 70px; font-size:32px;">
                        <?= substr($_SESSION['user']['prenom'], 0, 1) ?>
                    </div>
                    <div class="ms-3 flex-grow-1">
                        <p class="mb-1 fw-semibold"><?= $_SESSION['user']['prenom'] . " " . $_SESSION['user']['nom']; ?></p>
                        <p class="text-muted small mb-0"><?= $_SESSION['user']['email']; ?></p>
                    </div>
                </div>
                <a href="<?= $router->generate('edit_account'); ?>">
                    <button class="btn btn-outline-secondary">Modifier le profil</button>
                </a>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Role</label>
                <select class="form-select mb-2" disabled>
                    <option>Client</option>
                    <option>Collectivite</option>
                    <option>Association</option>
                    <option>Administrateur</option>
                </select>
                <div class="p-3 bg-primary bg-opacity-10 rounded">
                Nous adaptons votre expérience pour qu’elle réponde au mieux à aux besoins de nos utilisateurs. Vous pouvez modifier votre profil à tout moment.
                </div>
            </div>
        </div>

        <div class="card shadow-sm p-4 pt-0 mx-auto" style="max-width: 700px;">
            <h5 class="mt-5 fw-bold">Comptes associés</h5>
            <p class="text-muted">Méthodes que vous utilisez pour vous connecter au site</p>
            
            <div class="card p-3 mb-4 shadow-sm border-0">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="rounded bg-secondary bg-opacity-10 d-flex justify-content-center align-items-center" style="min-width: 50px; width:50px; height:50px;">
                            <span class="fw-bold">ID</span>
                        </div>
                        
                        <div class="ms-3">
                            <p class="fw-semibold mb-0">Connexion classique</p>
                            <p class="text-muted small mb-0">Email + Mot de passe</p>
                        </div>
                    </div>
                    
                    <a href="<?= $router->generate('logout'); ?>">
                        <button class="btn btn-outline-secondary">Se déconnecter</button>
                    </a>
                </div>
            </div>


            <h5 class="fw-bold text-danger mt-4">Suppression du compte</h5>
            <div class="card p-3 shadow-sm border-0 mb-4">
                <p class="mb-2">Vous pouvez supprimer définitivement votre compte et toutes les données associées.</p>
                <button id="logout-btn" class="btn btn-danger">Supprimer mon compte</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const logout_btn = document.querySelector('#logout-btn');

            logout_btn.addEventListener('click', () => {
                alert('Fonctionnalité en développement.');
            });
        });
    </script>
</body>
</html>