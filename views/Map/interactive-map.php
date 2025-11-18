<!DOCTYPE html>
<html lang="fr">

<?php require_once dirname(dirname(__DIR__)) . '/views/Includes/meta.php'; ?>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<link rel="stylesheet" href="/public/css/leafletMap.css"/>

<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.3.0/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.3.0/dist/MarkerCluster.Default.css" />
<script defer src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script defer src="https://unpkg.com/leaflet.markercluster@1.3.0/dist/leaflet.markercluster.js"></script>
<script defer src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script defer src="/public/js/Leaflet/leafletMap.js"></script>

<body>

    <?php require_once __DIR__ . './../Components/navbar.php'; ?>

    <main class="d-flex">
        <div class="d-flex flex-column flex-shrink-0 p-3 bg-light" style="width: 42vh;">
            <h5 class="fs-5 fw-semibold">Filtrer les équipements</h5>
            <form class="w-100" method="GET" action="">

                <div class="input-group mb-3">
                    <input type="search" name="search" id="searchInput" class="form-control" placeholder="Chercher un équipement..." aria-label="Chercher">
                </div>

                <h6>Activités</h6>
                <div class="btn-group-vertical w-100">
                    <button type="button" class="btn btn-outline-primary filter-activity-btn rounded-pill" name="activite" value="">
                        Tous
                    </button>
                    <?php foreach ($activities as $activity): ?>
                        <button type="button" class="btn btn-outline-primary filter-activity-btn rounded-pill" name="activite" value="<?php echo htmlspecialchars($activity['activites']); ?>">
                            <?php echo htmlspecialchars($activity['activites']); ?>
                        </button>
                    <?php endforeach; ?>
                </div>

                <hr>
                <button type="submit" class="btn btn-primary w-100">Appliquer les filtres</button>
            </form>
        </div>

        <!-- Leaflet Map -->
        <div id="map" class="resize-map"></div>

    </main>
</body>
</html>