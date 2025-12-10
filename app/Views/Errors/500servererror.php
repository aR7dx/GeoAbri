<!DOCTYPE html>
<html lang="fr">
<?php http_response_code(500); ?>

<?php require_once dirname(dirname(__DIR__)) . '/Views/Includes/meta.php'; ?>

<body class="bg-light">

    <main class="container d-flex align-items-center justify-content-center" style="height: 100vh;">

        <div class="px-5 py-5 text-center bg-light rounded shadow">
            <div class="d-flex flex-column gap-0">
                <h1 class="m-0">Erreur 500</h1>
            </div>
            
            <div class="d-flex my-3">
                <span>Une erreur serveur est survenue.</span>
            </div>
            
           <div>
                <button type="button" class="btn btn-primary">
                    <a class="text-decoration-none text-white" href="<?= $router->generate('home'); ?>">Retourner à l'accueil</a>    
                </button>
            </div>
        </div>

    </main>
</body>
</html>
