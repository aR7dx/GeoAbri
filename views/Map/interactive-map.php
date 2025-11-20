<!DOCTYPE html>
<html lang="fr">

<?php require_once dirname(dirname(__DIR__)) . '/views/Includes/meta.php'; ?>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<link rel="stylesheet" href="/public/css/leafletMap.css"/>
<link rel="stylesheet" href="/public/css/root.css"/>

<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.3.0/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.3.0/dist/MarkerCluster.Default.css" />
<script defer src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script defer src="https://unpkg.com/leaflet.markercluster@1.3.0/dist/leaflet.markercluster.js"></script>
<script defer src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script defer src="/public/js/Leaflet/ui.js"></script>
<script defer src="/public/js/Leaflet/leafletMap.js"></script>

<body>

    <?php require_once __DIR__ . './../Components/navbar.php'; ?>

    <main class="d-flex">

        <!-- menu flottant -->
        <div id="floating-panel" class="container position-absolute py-3 px-4">

            <div id="search-menu" class="<?= !empty($equipement) ? 'hidden-menu d-none' : 'd-flex' ?> flex-column gap-2">
                <!-- barre de recherche -->
                <div class="row justify-content-center">
                    <div class="search-container position-relative shadow">
                        <form class="d-flex align-items-center" method="GET">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="search-icon"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                            <input id="search-input" name="q" class="form-control search-input ps-5" type="search" placeholder="Rechercher un équipement..."
                            value="<?= isset($query) ? htmlspecialchars($query) : ''; ?>">
                            <button class="btn btn-search ms-2" type="button">Search</button>
                        </form>
                    </div>
                </div>

                <!-- suggestions -->
                <?php if (isset($suggestions) && !empty($suggestions)): ?>
                    <div class="row justify-content-center">
                        <div id="suggestions-list" class="search-container position-relative d-flex flex-column gap-2 p-3 shadow">
                            <p class="m-1 ms-2"><strong>Suggestions (<?= count($suggestions) ?>):</strong></p>
                            <?php foreach ($suggestions as $suggestion): ?>
                                <a href="/map?id=<?= $suggestion['id']; ?>" class="py-1 suggestions-items text-decoration-none text-black">
                                    <div class="d-flex align-items-center position-relative gap-4">
                                            <p class="suggestions-items-icon position-relative">📍</p>
                                        <div class="d-flex flex-column ms-2">
                                            <span><strong><?= htmlspecialchars($suggestion['name']); ?></strong></span>
                                            <small><?= htmlspecialchars($suggestion['commune'] ?? 'Inconnu'); ?></small>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php elseif (isset($suggestions) && empty($suggestions) && $query !== null): ?>
                    <div class="alert alert-danger text-center" role="alert">
                        <p class="m-0">Aucun équipement trouvé.</p>
                    </div>
                <?php endif; ?>
            </div>


            <div id="equipement-menu" class="<?= !empty($equipement) ? 'show-menu d-flex' : 'd-none' ?> equipement-info bg-white m-0 shadow flex-column h-full">
                <div class="equipement-menu-header d-flex justify-content-between bg-light py-3 px-4">
                    <span><strong id="span-equipement-name"><?= htmlspecialchars($equipement['nom'] ?? 'N/A'); ?></strong></span>
                    <div id="back-button" style="cursor: pointer;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                        </svg>
                    </div>
                </div>
                <div class="equipement-menu-body">
                    <div class="bg-black" style="height: 150px;"></div>
                </div>
            </div>
        </div>

        <!-- Leaflet Map -->
        <div id="map" class="resize-map"></div>

    </main>
</body>
</html>