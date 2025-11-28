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
                    <span>mon compte</span>
                </div>  
            </a>  
        </div>

        <div class="card shadow-sm p-4 mx-auto" style="max-width: 700px;">
            <h5 class="mt-3 mb-4 fw-bold">Modification de votre profil</h5>

            <div class="d-flex justify-content-between align-items-center mb-4 row-to-column">

                <div class="d-flex flex-row justify-content-left align-items-center w-100">
                    <div class="rounded-circle bg-danger text-white d-flex justify-content-center align-items-center" style=" min-width: 70px; width:70px; height:70px; font-size:25px;">
                        <span><?= strtoupper(substr($_SESSION['user']['prenom'], 0, 1) . substr($_SESSION['user']['nom'], 0, 1)); ?></span>
                    </div>
                
                    <div class="ms-3 flex-grow-1">
                        <p class="mb-1 fw-semibold">Importer votre photo de profil</p>
                        <p class="text-muted small mb-0">La photo aide à vous reconnaître dans GeoAbri.</p>
                    </div>
                </div>

                <a class="d-flex justify-content-end text-decoration-none w-50">
                    <button class="btn btn-outline-secondary " disabled>Importer une photo</button>
                </a>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Prénom</label>
                <div class="input-group">
                    <input type="text" class="form-control" value="<?= $_SESSION['user']['prenom']; ?>" disabled/>
                    <button class="btn btn-outline-secondary" disabled>Modifier</button>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Nom</label>
                <div class="input-group">
                    <input type="text" class="form-control" value="<?= $_SESSION['user']['nom']; ?>" disabled/>
                    <button class="btn btn-outline-secondary" disabled>Modifier</button>
                </div>
            </div>


            <div class="mb-4">
                <label class="form-label fw-semibold">Adresse e-mail</label>
                <div class="input-group">
                    <input type="email" class="form-control" value="<?= $_SESSION['user']['email']; ?>" disabled/>
                    <button class="btn btn-outline-secondary" disabled>Modifier</button>
                </div>
            </div>


            <div class="mb-4">
                <label class="form-label fw-semibold">Téléphone</label>
                <div class="input-group">
                    <input type="tel" class="form-control" value="<?= $_SESSION['user']['telephone']; ?>" disabled/>
                    <button class="btn btn-outline-secondary" disabled>Modifier</button>
                </div>
            </div>


            <div class="mb-4">
                <label class="form-label fw-semibold">Ville</label>
                <div class="input-group">
                    <input type="text" class="form-control" value="<?= $_SESSION['user']['ville']; ?>" disabled/>
                    <button class="btn btn-outline-secondary" disabled>Modifier</button>
                </div>
            </div>


            <div class="mb-4">
                <label class="form-label fw-semibold">Code Postal</label>
                <div class="input-group">
                    <input type="text" class="form-control" value="<?= $_SESSION['user']['code_postal']; ?>" disabled/>
                    <button class="btn btn-outline-secondary" disabled>Modifier</button>
                </div>
            </div>

        </div>
    </div>

</body>
</html>