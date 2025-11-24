<!DOCTYPE html>
<html lang="fr">
<?php http_response_code(404); ?>

<?php require_once dirname(dirname(__DIR__)) . '/Views/Includes/meta.php'; ?>

<body class="bg-light">

    <main class="container d-flex align-items-center justify-content-center" style="height: 100vh;">

        <div class="px-5 py-5 text-center bg-light rounded shadow">
            <h1>Erreur 404</h1>
            <p>La page que vous demandez n'existe pas</p>    
            
           <div>
                <button type="button" class="btn btn-primary">
                    <a class="text-decoration-none text-white" href="<?= $router->generate('home'); ?>">Retourner à l'accueil</a>    
                </button>
            </div>
        </div>

    </main>
</body>
</html>
