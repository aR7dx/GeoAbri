<!DOCTYPE html>
<html lang="fr">
<?php http_response_code(403); ?>

<?php require_once dirname(dirname(__DIR__)) . '/Views/Includes/meta.php'; ?>

<body class="bg-light">

    <main class="container d-flex align-items-center justify-content-center" style="height: 100vh;">

        <div class="px-5 py-5 text-center bg-light rounded shadow">
            <h1>Erreur 403</h1>
            <p>Vous n'avez pas accès à cette page</p>    
            
           <div>
                <button type="button" class="btn btn-primary">
                    <a class="text-decoration-none text-white" href="<?= $router->generate('home'); ?>">Retourner à l'accueil</a>    
                </button>
            </div>
        </div>

    </main>
</body>
</html>
