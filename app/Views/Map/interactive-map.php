<!DOCTYPE html>
<html lang="fr">

<?php require_once dirname(dirname(__DIR__)) . '/Views/Includes/meta.php'; ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<link rel="stylesheet" href="/public/css/leafletMap.css"/>

<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.3.0/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.3.0/dist/MarkerCluster.Default.css" />
<script defer src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script defer src="https://unpkg.com/leaflet.markercluster@1.3.0/dist/leaflet.markercluster.js"></script>

<script defer src="/public/js/Leaflet/fetchData.js"></script>
<script defer src="/public/js/Leaflet/filters.js"></script>
<script defer src="/public/js/Leaflet/ui.js"></script>
<script defer src="/public/js/Leaflet/map.js"></script>

<script defer src="/public/js/CheckForms/checkMapSearchForm.js"></script>

<body>

    <?php require_once __DIR__ . './../Components/navbar.php'; ?>

    <main class="d-flex">

        <!-- menu flottant -->
        <div id="floating-panel" class="container position-absolute py-3 px-4" style="z-index: 1025 !important">

            <div id="search-menu" class="<?= !empty($equipement) ? 'hidden-menu d-none' : 'd-flex' ?> flex-column gap-2">
                <!-- barre de recherche -->
                <form class="d-flex flex-column gap-2" method="GET">
                
                    <div class="d-flex flex-column row justify-content-center align-items-center gap-2">
                        <div class="search-container position-relative shadow">
                            <div class="d-flex align-items-center">
                                <svg class="search-icon ms-2" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                <input id="search-input" name="q" class="form-control search-input ps-5" type="search" placeholder="Rechercher un équipement, ville, code postal..." autofocus>
                                
                                <div class="d-flex gap-1 align-items-center">
                                    <!-- bouton parametres avancés-->
                                    <svg id="search-options-btn" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-sliders" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M11.5 2a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3M9.05 3a2.5 2.5 0 0 1 4.9 0H16v1h-2.05a2.5 2.5 0 0 1-4.9 0H0V3zM4.5 7a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3M2.05 8a2.5 2.5 0 0 1 4.9 0H16v1H6.95a2.5 2.5 0 0 1-4.9 0H0V8zm9.45 4a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3m-2.45 1a2.5 2.5 0 0 1 4.9 0H16v1h-2.05a2.5 2.5 0 0 1-4.9 0H0v-1z"/></svg>
                                    <!-- bouton envoie formulaire -->
                                    <button class="btn btn-search" type="submit">Chercher</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- filtres avancées -->
                    <div class="row justify-content-center">
                        <div id="advanced-filters" class="search-container position-relative flex-column gap-3 p-4 shadow" style="display: none;">

                            <!-- Catégorie d'équipement -->
                            <div class="d-flex flex-column">
                                <label class="form-label fw-semibold mb-1 user-select-none">Catégories d'équipement</label>
                                <select id="category-select" class="form-select bg-light" name="category">
                                    <option value="">Chargement...</option>
                                </select>
                            </div>

                            <!-- Accessibilité PMR -->
                            <div class="d-flex flex-column">
                                <label class="form-label fw-semibold mb-1 mt-2 user-select-none">Accessibilité PMR + sensoriel</label>
                                <select class="form-select bg-light" name="pmr">
                                    <option value="" <?= !isset($_GET['pmr']) || $_GET['pmr'] === '' ? 'selected' : '' ?>>Tous</option>
                                    <option value="oui" <?= isset($_GET['pmr']) && $_GET['pmr'] === 'oui' ? 'selected' : '' ?>>Oui</option>
                                    <option value="non" <?= isset($_GET['pmr']) && $_GET['pmr'] === 'non' ? 'selected' : '' ?>>Non</option>
                                </select>
                            </div>

                            <!-- État -->
                            <div class="d-flex flex-column">
                                <label class="form-label fw-semibold mb-1 mt-2 user-select-none">État</label>
                                <select class="form-select bg-light" name="etat">
                                    <option value="" <?= !isset($_GET['etat']) || $_GET['etat'] === '' ? 'selected' : '' ?>>Tous les états</option>
                                    <option value="valide" <?= isset($_GET['etat']) && $_GET['etat'] === 'valide' ? 'selected' : '' ?>>Validé</option>
                                    <option value="attente" <?= isset($_GET['etat']) && $_GET['etat'] === 'attente' ? 'selected' : '' ?>>En attente</option>
                                </select>
                            </div>

                            <!-- Activités -->
                            <div class="d-flex flex-column">
                                <label class="form-label fw-semibold mb-1 mt-2 user-select-none">Activités</label>
                                <select id="activites-select" class="form-select bg-light" name="activites">
                                    <option value="">Chargement...</option>
                                </select>
                            </div>

                            <!-- Accès libre -->
                            <div class="d-flex flex-column">
                                <label class="form-label fw-semibold mb-1 mt-2 user-select-none">Accès libre</label>
                                <select class="form-select bg-light" name="acces_libre">
                                    <option value="" <?= !isset($_GET['acces_libre']) || $_GET['acces_libre'] === '' ? 'selected' : '' ?>>Tous</option>
                                    <option value="oui" <?= isset($_GET['acces_libre']) && $_GET['acces_libre'] === 'oui' ? 'selected' : '' ?>>Oui</option>
                                    <option value="non" <?= isset($_GET['acces_libre']) && $_GET['acces_libre'] === 'non' ? 'selected' : '' ?>>Non</option>
                                </select>
                            </div>

                            <!-- Rayon -->
                            <div class="d-flex flex-column">
                                <label for="range" class="form-label fw-semibold mb-1 mt-2 user-select-none">Rayon</label>
                                <div class="d-flex flex-row gap-2">
                                    <span class="user-select-none">0</span>
                                    <input id="range-input" type="range" class="form-range" name="range" min="0" max="100" value="<?= isset($_GET['range']) && is_numeric($_GET['range']) && $_GET['range'] >= 0 && $_GET['range'] <= 100 ? $_GET['range'] : 100 ?>">
                                    <output id="range-input-label" for="range" class="user-select-none" aria-hidden="true"><?= isset($_GET['range']) && is_numeric($_GET['range']) && $_GET['range'] >= 0 && $_GET['range'] <= 100 ? ((int)$_GET['range'] * 2) : "200" ?>km</output>
                                </div>
                            </div>

                            <!-- Commune -->
                            <div class="d-flex flex-column">
                                <label class="form-label fw-semibold mb-1 mt-2 user-select-none">Commune</label>
                                <input type="text" class="form-control bg-light" name="commune" placeholder="Ex: Paris, Lyon" value="<?= isset($_GET['commune']) && $_GET['commune'] !== '' ? $_GET['commune'] : '' ?>">
                            </div>

                            <!-- Réinitialiser -->
                            <div class="d-flex flex-column mt-3">
                                <a href="<?= $router->generate('map'); ?>" class="d-flex flex-row w-100 text-decoration-none">
                                    <button id="reset-filters-btn" type="button" class="btn btn-danger w-100">Réinitialiser</button>                                    
                                </a>
                            </div>

                        </div>
                    </div>
                </form>

                <!-- suggestions -->
                <div id="suggestions-results" class="row justify-content-center d-none">
                    <div id="suggestions-list" class="search-container position-relative d-flex flex-column gap-2 p-3 shadow"></div>
                </div>
                
                <div id="search-no-results" class="alert alert-danger text-center d-none" role="alert">
                    <p class="m-0">Aucun résultat.</p>
                </div>
                
            </div>


            <div id="equipement-menu" class="d-none equipement-info bg-white m-0 shadow d-flex flex-column" style="max-height: 85vh;">
                <div class="equipement-menu-header d-flex justify-content-between bg-light py-3 px-4 flex-shrink-0">
                    <strong><span id="span-equipement-name"><?= htmlspecialchars($equipement['nom'] ?? 'N/A'); ?></span></strong>
                    <div id="back-button" style="cursor: pointer;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/></svg>
                    </div>
                </div>
                <div class="equipement-menu-body overflow-auto" style="flex: 1; min-height: 0;">
                    <div id="equipement-display-img" class="d-flex justify-content-center align-items-center bg-black text-light" style="height: 187px; user-select: none;">

                        <div class="d-flex justify-content-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Chargement...</span>
                            </div>
                        </div>

                    </div>
                    
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active text-primary-subtle" data-bs-toggle="tab" data-bs-target="#presentation" type="button" role="tab" aria-controls="presentation" aria-selected="true">
                                Présentation
                            </button>
                        </li>

                        <li class="nav-item" role="reservation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#reservation" type="button" role="tab" aria-controls="reservation" aria-selected="false">
                                Réservation
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
                            <div id="equipement-itinerary-container"></div>
                            <hr>
                            
                            <!-- Jauge de capacité -->
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3"><i class="bi bi-people me-2"></i>Capacité d'accueil</h6>
                                <div id="equipement-capacity" class="d-flex justify-content-center"></div>
                            </div>
                            
                            <!-- Caractéristiques techniques -->
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3"><i class="bi bi-tools me-2"></i>Caractéristiques</h6>
                                <div id="equipement-technical" class="row g-2"></div>
                            </div>
                            
                            <!-- Commodités -->
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3"><i class="bi bi-check2-square me-2"></i>Équipements et commodités</h6>
                                <div id="equipement-amenities" class="row g-2"></div>
                            </div>
                            
                            <!-- Accessibilité -->
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3"><i class="bi bi-universal-access me-2"></i>Accessibilité</h6>
                                <div id="equipement-accessibility" class="row g-2"></div>
                            </div>
                            
                            <!-- Dimensions -->
                            <div class="mb-4" id="equipement-dimensions-section" style="display: none;">
                                <h6 class="fw-bold mb-3"><i class="bi bi-rulers me-2"></i>Dimensions</h6>
                                <div id="equipement-dimensions" class="row g-2"></div>
                            </div>
                        
                        </div>

                        <div class="tab-pane fade py-3 px-3" id="reservation" role="tabpanel" aria-labelledby="reservation-tab">
                            <a class="text-decoration-none w-100" href="<?= isset($_SESSION['user']['connected']) ? '#' : $router->generate('login'); ?>">
                                <button class="btn bg-primary-subtle w-100">Réserver</button>
                            </a>
                        </div>
                        
                        <div class="tab-pane fade py-3 px-3" id="about" role="tabpanel" aria-labelledby="about-tab">
                            <!-- Informations générales -->
                            <div class="d-flex flex-column gap-3">
                                <div>
                                    <h6 class="fw-bold mb-2"><i class="bi bi-info-circle me-2"></i>Description</h6>
                                    <div id="equipement-description" class="text-muted"></div>
                                </div>
                                
                                <div class="d-flex flex-column gap-2 card bg-primary-subtle p-2">
                                    <div>
                                        <h6 class="fw-bold mb-2">Localisation:</h6>
                                        <div id="equipement-location" class="text-dark"></div>
                                    </div>
                                    
                                    <div>
                                        <h6 class="fw-bold mb-2">Contact:</h6>
                                        <div id="equipement-email"></div>
                                    </div>
                                </div>
                            </div>
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