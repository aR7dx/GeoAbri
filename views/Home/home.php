<!DOCTYPE html>
<html lang="fr">

<?php require_once dirname(dirname(__DIR__)) . '/views/Includes/meta.php'; ?>

<body>

    <?php require_once __DIR__ . './../Components/navbar.php'; ?>
    
    <main class="container">
        <div class="px-4 py-5 my-5 text-center bg-light">
            <h1 class="display-5 fw-bold text-body-emphasis">Trouvez votre équipement</h1>
            
            <div class="col-lg-6 mx-auto">
                <p class="lead mb-4">Recherchez parmi les équipements publics en France par localisation et caractéristiques</p>
            </div>

            <div>
                <button type="button" class="btn btn-primary">
                    <a class="text-decoration-none text-white" href="<?= $router->generate('map'); ?>">Me localiser</a>    
                </button>
            </div>
        </div>

    </main>

    <?php require_once __DIR__ . './../Components/footer.php'; ?>

</body>
</html>