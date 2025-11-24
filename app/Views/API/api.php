<!DOCTYPE html>
<html lang="fr">

<?php require_once dirname(dirname(__DIR__)) . '/Views/Includes/meta.php'; ?>

<body>

    <?php require_once __DIR__ . './../Components/navbar.php'; ?>

    <div class="container mt-5">
        <h3>Bienvenue sur l'API</h3>

        <p>Comment contacter l'api ?</p>
        <p class="card p-2">/api/map-equipements?minLat={minLat}&maxLat={maxLat}&minLon={minLon}&maxLon={maxLon}</p>
        <p class="card p-2">/api/map-suggestions?q={query}&minLat={minLat}&maxLat={maxLat}&minLon={minLon}&maxLon={maxLon}</p>

    </div>

</body>
</html>