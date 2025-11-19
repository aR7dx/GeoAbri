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

        <!-- menu flottant -->
        <div class="container d-flex flex-column gap-2 position-absolute py-3 px-4" style="z-index: 500; width: fit-content;">

            <!-- barre de recherche -->
            <div class="row justify-content-center">
                <div class="search-container position-relative">
                    <form class="d-flex align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="search-icon feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        <input class="form-control search-input ps-5" type="search" placeholder="Rechercher un équipement...">
                        <button class="btn btn-search ms-2" type="button">Search</button>
                    </form>
                </div>
            </div>
            <!-- fin barre de recherche -->

            <?php if (isset($suggestions) && !empty($suggestions)): ?>
                <div class="row justify-content-center">
                    <div class="search-container position-relative d-flex flex-column gap-2">
                        <?php foreach ($suggestions as $suggestion): ?>
                            <div class="search-container-items d-flex align-items-center position-relative gap-4">
                                <p class="search-icon feather feather-search position-relative">🏟️</p>
                                <a href="/map?id=<?php echo $suggestion['id']; ?>" class="suggestion-link ms-2 text-decoration-none text-black">
                                    <strong><?php echo htmlspecialchars($suggestion['name']); ?></strong>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <!-- Fin menu flottant -->

        <!-- Leaflet Map -->
        <div id="map" class="resize-map"></div>

    </main>
</body>
</html>