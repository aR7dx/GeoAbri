<!DOCTYPE html>
<html lang="fr">

<?php require_once dirname(dirname(__DIR__)) . '/Views/Includes/meta.php'; ?>

<body>
    <main class="container d-flex align-items-center justify-content-center" style="height: 97vh;">

        <div class="d-flex card flex-column gap-4 p-4" style="min-width: 400px;">
             <div>
                <strong>
                    <a href="<?= $router->generate('home'); ?>" class="text-start text-decoration-none text-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/></svg>    
                        Retour à l'accueil
                    </a>
                </strong>
            </div>

            <div class="mb-1">
                <h1 class="text-start fw-bold">Connexion</h1>
            
                <span class="d-flex gap-1">
                    Ou
                    <a href="<?= $router->generate('register'); ?>" class="text-start text-decoration-none text-danger fw-bold" role="button">créez votre compte</a>
                </span>
            </div>


            <form method="POST" class="text-center rounded">
                <div class="mb-3">
                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    <input name="email" type="email" class="form-control" placeholder="Email" required autofocus>
                </div>
                <div class="mb-4">
                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                    <input name="password" type="password" class="form-control" placeholder="Mot de passe" required>
                </div>
                <div class="row">
                    <div>
                        <button type="submit" class="btn btn-warning px-4 py-2 w-100">Se connecter</button>
                    </div>
                </div>
            </form>
        </div>

    </main>

</body>
</html>