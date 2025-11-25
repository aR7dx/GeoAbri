<!DOCTYPE html>
<html lang="fr">

<?php require_once dirname(dirname(__DIR__)) . '/Views/Includes/meta.php'; ?>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<link rel="stylesheet" href="/public/css/leafletMap.css"/>
<link rel="stylesheet" href="/public/css/root.css"/>

<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.3.0/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.3.0/dist/MarkerCluster.Default.css" />
<script defer src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script defer src="https://unpkg.com/leaflet.markercluster@1.3.0/dist/leaflet.markercluster.js"></script>
<script defer src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script defer src="/public/js/Leaflet/fetchData.js"></script>
<script defer src="/public/js/Leaflet/ui.js"></script>
<script defer src="/public/js/Leaflet/map.js"></script>

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
                            value="<?= isset($query) ? htmlspecialchars($query) : ''; ?>" autofocus>
                            <button class="btn btn-search ms-2" type="submit">Search</button>
                        </form>
                    </div>
                </div>

                <!-- suggestions -->
                <div id="suggestions-results" class="row justify-content-center d-none">
                    <div id="suggestions-list" class="search-container position-relative d-flex flex-column gap-2 p-3 shadow"></div>
                </div>
                
                <div id="suggestions-no-results" class="alert alert-danger text-center d-none" role="alert">
                    <p class="m-0">Aucun résultats.</p>
                </div>
                
            </div>


            <div id="equipement-menu" class="<?= (isset($equipement) && !empty($equipement)) ? 'show-menu d-flex' : 'd-none' ?> equipement-info bg-white m-0 shadow flex-column h-full">
                <div class="equipement-menu-header d-flex justify-content-between bg-light py-3 px-4">
                    <strong><span id="span-equipement-name"><?= htmlspecialchars($equipement['nom'] ?? 'N/A'); ?></span></strong>
                    <div id="back-button" style="cursor: pointer;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                        </svg>
                    </div>
                </div>
                <div class="equipement-menu-body">
                    <div class="bg-black" style="height: 187px;">
                        <span class="position-absolute text-white" style="left: 100px;">Futur Google Image</span>
                    </div>
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#presentation" type="button" role="tab" aria-controls="presentation" aria-selected="true">
                                Présentation
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#avis" type="button" role="tab" aria-controls="avis" aria-selected="false">
                                Avis
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#about" type="button" role="tab" aria-controls="about" aria-selected="false">
                                A propos
                            </button>
                        </li>
                    </ul>
                        
                    <div class="tab-content">
                        <div class="tab-pane fade show active py-3 px-3" id="presentation" role="tabpanel" aria-labelledby="presentation-tab">
                            <?php if (isset($equipement['website']) && !empty($equipement['website'])): ?>
                                <div id="equipement-website-container" class="d-flex gap-3 align-items">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-globe-americas-fill" viewBox="0 0 16 16"><path fill-rule="evenodd" d="m8 0 .412.01A7.97 7.97 0 0 1 13.29 2a8.04 8.04 0 0 1 2.548 4.382 8 8 0 1 1-15.674 0 8 8 0 0 1 1.361-3.078A8 8 0 0 1 2.711 2 7.96 7.96 0 0 1 8 0m0 1a7 7 0 0 0-5.958 3.324C2.497 6.192 6.669 7.827 6.5 8c-.5.5-1.034.884-1 1.5.07 1.248 2.259.774 2.5 2 .202 1.032-1.051 3 0 3 1.5-.5 3.798-3.186 4-5 .138-1.242-2-2-3.5-2.5-.828-.276-1.055.648-1.5.5S4.5 5.5 5.5 5s1 0 1.5.5c1 .5.5-1 1-1.5.838-.838 3.16-1.394 3.605-2.001A6.97 6.97 0 0 0 8 1"/></svg>
                                    <strong><a id="span-equipement-website" href="<?= htmlspecialchars($equipement['website']); ?>" target="_blank"><?= htmlspecialchars($equipement['website'] ?? 'N/A'); ?></a></strong>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="tab-pane fade" id="avis" role="tabpanel" aria-labelledby="avis-tab">
                            <span id="">Section Avis:</span>
                        </div>
                        <div class="tab-pane fade" id="about" role="tabpanel" aria-labelledby="about-tab">
                            <span id="">Section A propos:</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Leaflet Map -->
        <div id="map" class="resize-map"></div>

    </main>
</body>
</html>