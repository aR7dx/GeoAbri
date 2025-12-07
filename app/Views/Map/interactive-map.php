<!DOCTYPE html>
<html lang="fr">

<?php require_once dirname(dirname(__DIR__)) . '/Views/Includes/meta.php'; ?>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<link rel="stylesheet" href="/public/css/leafletMap.css"/>

<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.3.0/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.3.0/dist/MarkerCluster.Default.css" />
<script defer src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script defer src="https://unpkg.com/leaflet.markercluster@1.3.0/dist/leaflet.markercluster.js"></script>

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
                <div class="d-flex flex-column row justify-content-center align-items-center gap-2">
                    <div class="search-container position-relative shadow">
                        <form class="d-flex align-items-center" method="GET">
                            <svg class="search-icon ms-2" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                            <input id="search-input" name="q" class="form-control search-input ps-5" type="search" placeholder="Rechercher un équipement, ville, code postal..." value="<?= isset($query) ? htmlspecialchars($query) : ''; ?>" autofocus>
                            
                            <div class="d-flex gap-1 align-items-center">
                                <!-- bouton parametres avancés-->
                                <svg id="search-options-btn" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-sliders" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M11.5 2a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3M9.05 3a2.5 2.5 0 0 1 4.9 0H16v1h-2.05a2.5 2.5 0 0 1-4.9 0H0V3zM4.5 7a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3M2.05 8a2.5 2.5 0 0 1 4.9 0H16v1H6.95a2.5 2.5 0 0 1-4.9 0H0V8zm9.45 4a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3m-2.45 1a2.5 2.5 0 0 1 4.9 0H16v1h-2.05a2.5 2.5 0 0 1-4.9 0H0v-1z"/></svg>
                                <!-- bouton envoie formulaire -->
                                <button class="btn btn-search" type="submit">Chercher</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- suggestions -->
                <div id="suggestions-results" class="row justify-content-center d-none">
                    <div id="suggestions-list" class="search-container position-relative d-flex flex-column gap-2 p-3 shadow"></div>
                </div>
                
                <div id="suggestions-no-results" class="alert alert-danger text-center d-none" role="alert">
                    <p class="m-0">Aucun résultat.</p>
                </div>
                
            </div>


            <div id="equipement-menu" class="d-none equipement-info bg-white m-0 shadow flex-column h-full">
                <div class="equipement-menu-header d-flex justify-content-between bg-light py-3 px-4">
                    <strong><span id="span-equipement-name"><?= htmlspecialchars($equipement['nom'] ?? 'N/A'); ?></span></strong>
                    <div id="back-button" style="cursor: pointer;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/></svg>
                    </div>
                </div>
                <div class="equipement-menu-body">
                    <div id="equipement-display-img" class="d-flex justify-content-center align-items-center bg-black text-light" style="height: 187px; user-select: none;">

                        <div class="d-flex justify-content-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Chargement...</span>
                            </div>
                        </div>

                    </div>
                    
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#presentation" type="button" role="tab" aria-controls="presentation" aria-selected="true">
                                Présentation
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
                            <div id="equipement-website-container" class="d-flex gap-3 mb-3 align-items"></div>

                            <div id=equipement-itinerary-container></div>
                            <hr>
                            <div class="d-flex flex-column gap-2">
                                <div id="equipement-description"></div>
                                <div class="d-flex flex-column gap-1">
                                    <div id="equipement-email"></div>
                                </div>
                            </div>
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