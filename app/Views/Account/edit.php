<!DOCTYPE html>
<html lang="fr">

<?php require_once dirname(dirname(__DIR__)) . '/Views/Includes/meta.php'; ?>

<body>

    <?php require_once __DIR__ . './../Components/navbar.php'; ?>

    <div class="container py-5">
        <div class="d-flex flex-column justify-content-between align-items-center mb-5 gap-4">

            <a href="<?= $router->generate('account'); ?>" class="text-decoration-none">
                <div class="d-flex flex-row align-items-center gap-3" style="cursor: pointer; user-select: none;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/></svg>
                    <span>mon compte</span>
                </div>  
            </a>  

            <h1 class="text-center m-0">Modification de votre profil</h1>
        </div>

        <div class="card shadow-sm p-4 mx-auto" style="max-width: 700px;">
            <div class="d-flex align-items-center mb-4">
                <div class="rounded-circle bg-danger text-white d-flex justify-content-center align-items-center" style="width:70px; height:70px; font-size:32px;">
                    F
                </div>
            
                <div class="ms-3 flex-grow-1">
                    <p class="mb-1 fw-semibold">Importer votre photo de profil</p>
                    <p class="text-muted small mb-0">La photo aide à vous reconnaître dans GeoAbri.</p>
                </div>
                <button class="btn btn-outline-secondary">Importer une photo</button>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Prénom</label>
                <div class="input-group">
                    <input type="text" class="form-control" value="<?= $_SESSION['user']['prenom']; ?>" />
                    <button class="btn btn-outline-secondary">Modifier</button>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Nom</label>
                <div class="input-group">
                <input type="text" class="form-control" value="<?= $_SESSION['user']['nom']; ?>" />
                <button class="btn btn-outline-secondary">Modifier</button>
            </div>
        </div>


        <div class="mb-4">
            <label class="form-label fw-semibold">Adresse e-mail</label>
            <div class="input-group">
                <input type="email" class="form-control" value="<?= $_SESSION['user']['email']; ?>" />
                <button class="btn btn-outline-secondary">Modifier</button>
            </div>
        </div>


        <div class="mb-4">
            <label class="form-label fw-semibold">Téléphone</label>
            <div class="input-group">
                <input type="text" class="form-control" value="" disabled />
                <button class="btn btn-outline-secondary">Modifier</button>
            </div>
        </div>


        <div class="mb-4">
            <label class="form-label fw-semibold">Ville</label>
            <div class="input-group">
                <input type="text" class="form-control" value="" disabled />
                <button class="btn btn-outline-secondary">Modifier</button>
            </div>
        </div>


<div class="mb-4">
<label class="form-label fw-semibold">Code Postal</label>
<div class="input-group">
<input type="text" class="form-control" value="" disabled />
<button class="btn btn-outline-secondary">Modifier</button>
</div>
</div>



</div>
</div>

</body>
</html>