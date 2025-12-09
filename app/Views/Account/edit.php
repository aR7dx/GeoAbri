<!DOCTYPE html>
<html lang="fr">

<?php require_once dirname(dirname(__DIR__)) . '/Views/Includes/meta.php'; ?>

<body>

    <?php require_once __DIR__ . './../Components/navbar.php'; ?>

    <div class="container pt-4 pb-5">
        <div class="d-flex flex-column justify-content-start align-items-left mb-4 ms-2 gap-4">

            <a href="<?= $router->generate('account'); ?>" class="text-decoration-none">
                <div class="d-flex flex-row align-items-center gap-3 text-primary" style="cursor: pointer; user-select: none;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/></svg>
                    <span>Mon compte</span>
                </div>  
            </a>  
        </div>

        <div class="card shadow-sm p-4 mx-auto" style="max-width: 700px;">
            <h5 class="mt-3 mb-4 fw-bold">Modification de votre profil</h5>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Erreur !</strong> <?= htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($success)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Succès !</strong> <?= htmlspecialchars($success); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= $router->generate('edit_account_submit'); ?>">
                
                <div class="d-flex justify-content-between align-items-center mb-4 row-to-column">
                    <div class="d-flex flex-row justify-content-left align-items-center w-100">
                        <div class="rounded-circle bg-danger text-white d-flex justify-content-center align-items-center" style=" min-width: 70px; width:70px; height:70px; font-size:25px;">
                            <span><?= strtoupper(substr($_SESSION['user']['prenom'], 0, 1) . substr($_SESSION['user']['nom'], 0, 1)); ?></span>
                        </div>
                    
                        <div class="ms-3 flex-grow-1">
                            <p class="mb-1 fw-semibold">Photo de profil</p>
                            <p class="text-muted small mb-0">La photo aide à vous reconnaître dans GeoAbri.</p>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end w-50">
                        <button class="btn btn-outline-secondary" type="button" disabled>Importer une photo</button>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="prenom" class="form-label fw-semibold">Prénom <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="prenom" name="prenom" value="<?= htmlspecialchars($_SESSION['user']['prenom']); ?>" required/>
                </div>

                <div class="mb-4">
                    <label for="nom" class="form-label fw-semibold">Nom <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nom" name="nom" value="<?= htmlspecialchars($_SESSION['user']['nom']); ?>" required/>
                </div>

                <div class="mb-4">
                    <label for="email" class="form-label fw-semibold">Adresse e-mail <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($_SESSION['user']['email']); ?>" required/>
                    <small class="text-muted">Votre adresse e-mail sert d'identifiant de connexion.</small>
                </div>

                <div class="mb-4">
                    <label for="telephone" class="form-label fw-semibold">Téléphone <span class="text-danger">*</span></label>
                    <input type="tel" class="form-control" id="telephone" name="telephone" value="<?= htmlspecialchars($_SESSION['user']['telephone']); ?>" required pattern="[0-9]{10}" placeholder="0123456789"/>
                    <small class="text-muted">Format : 10 chiffres sans espaces.</small>
                </div>

                <div class="mb-4">
                    <label for="ville" class="form-label fw-semibold">Ville <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="ville" name="ville" value="<?= htmlspecialchars($_SESSION['user']['ville']); ?>" required/>
                </div>

                <div class="mb-4">
                    <label for="code_postal" class="form-label fw-semibold">Code Postal <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="code_postal" name="code_postal" value="<?= htmlspecialchars($_SESSION['user']['code_postal']); ?>" required pattern="[0-9]{5}" placeholder="75001"/>
                    <small class="text-muted">Format : 5 chiffres.</small>
                </div>

                <hr class="my-4">

                <h6 class="fw-bold mb-3">Modifier le mot de passe (optionnel)</h6>
                <p class="text-muted small mb-3">Laissez vide si vous ne souhaitez pas changer votre mot de passe.</p>

                <div class="mb-4">
                    <label for="current_password" class="form-label fw-semibold">Mot de passe actuel</label>
                    <input type="password" class="form-control" id="current_password" name="current_password" placeholder="Entrez votre mot de passe actuel"/>
                </div>

                <div class="mb-4">
                    <label for="new_password" class="form-label fw-semibold">Nouveau mot de passe</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Minimum 8 caractères" minlength="8"/>
                </div>

                <div class="mb-4">
                    <label for="confirm_password" class="form-label fw-semibold">Confirmer le nouveau mot de passe</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirmez le nouveau mot de passe" minlength="8"/>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <a href="<?= $router->generate('account'); ?>" class="btn btn-outline-secondary">
                        Annuler
                    </a>
                    <button type="submit" class="btn btn-success">
                        Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Validation des mots de passe côté client
        document.querySelector('form').addEventListener('submit', function(e) {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const currentPassword = document.getElementById('current_password').value;

            // Si un nouveau mot de passe est saisi
            if (newPassword || confirmPassword) {
                // Le mot de passe actuel doit être renseigné
                if (!currentPassword) {
                    e.preventDefault();
                    alert('Veuillez entrer votre mot de passe actuel pour modifier votre mot de passe.');
                    return false;
                }

                // Les deux nouveaux mots de passe doivent correspondre
                if (newPassword !== confirmPassword) {
                    e.preventDefault();
                    alert('Les nouveaux mots de passe ne correspondent pas.');
                    return false;
                }

                // Le nouveau mot de passe doit être différent de l'actuel
                if (newPassword === currentPassword) {
                    e.preventDefault();
                    alert('Le nouveau mot de passe doit être différent de l\'ancien.');
                    return false;
                }
            }
        });
    </script>

</body>
</html>