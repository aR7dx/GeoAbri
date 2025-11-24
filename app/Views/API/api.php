<!DOCTYPE html>
<html lang="fr">

<?php require_once dirname(dirname(__DIR__)) . '/Views/Includes/meta.php'; ?>

<body>

    <?php require_once __DIR__ . './../Components/navbar.php'; ?>

    <div class="container mt-5">
        <div class="d-flex flex-column gap-0 mb-5">
            <h2 class="m-0">GeoAbri-API</h2>
            <span>API de l'application web GeoAbri.</span>
        </div>


        <div class="d-flex flex-column gap-3">
            <h3>Equipements</h3>
            <div class="card p-3 d-flex flex-row gap-2 align-items-center alert alert-primary">
                <div class="card p-1 px-2 bg-primary text-white" style="min-width: 48px;">GET</div>
                <span><strong>/api/map-equipements</strong>?minLat=<strong>{minLat}</strong>&maxLat=<strong>{maxLat}</strong>&minLon=<strong>{minLon}</strong>&maxLon=<strong>{maxLon}</strong></span>
            </div>

            <h3>Suggestions</h3>
            <span class="card p-3 d-flex flex-row gap-2 align-items-center alert alert-primary">
                <div class="card p-1 px-2 bg-primary text-white" style="min-width: 48px;">GET</div>
                <span><strong>/api/map-suggestions</strong>?q=<strong>{query}</strong>&minLat=<strong>{minLat}</strong>&maxLat=<strong>{maxLat}</strong>&minLon=<strong>{minLon}</strong>&maxLon=<strong>{maxLon}</strong></span>
            </span>
        </div>
    </div>

</body>
</html>