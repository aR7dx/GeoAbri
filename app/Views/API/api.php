<!DOCTYPE html>
<html lang="fr">

<?php require_once dirname(dirname(__DIR__)) . '/Views/Includes/meta.php'; ?>

<style>
    .endpoint-card {
        border-left: 4px solid #0d6efd;
        transition: all 0.2s ease;
    }
    .endpoint-card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    .method-badge {
        font-weight: 600;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }
    .code-block {
        background-color: #f8f9fa;
        border-radius: 0.375rem;
        padding: 1rem;
        font-family: 'Courier New', monospace;
        font-size: 0.875rem;
        overflow-x: auto;
    }
    .param-badge {
        background-color: #e7f1ff;
        color: #0d6efd;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.875rem;
        font-family: monospace;
    }
</style>

<body>

    <?php require_once __DIR__ . './../Components/navbar.php'; ?>

    <!-- Hero Section -->
    <div class="bg-light text-dark py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-code-slash mb-2" viewBox="0 0 16 16"><path d="M10.478 1.647a.5.5 0 1 0-.956-.294l-4 13a.5.5 0 0 0 .956.294zM4.854 4.146a.5.5 0 0 1 0 .708L1.707 8l3.147 3.146a.5.5 0 0 1-.708.708l-3.5-3.5a.5.5 0 0 1 0-.708l3.5-3.5a.5.5 0 0 1 .708 0m6.292 0a.5.5 0 0 0 0 .708L14.293 8l-3.147 3.146a.5.5 0 0 0 .708.708l3.5-3.5a.5.5 0 0 0 0-.708l-3.5-3.5a.5.5 0 0 0-.708 0"/></svg>
                        Documentation API GeoAbri
                    </h1>
                    <p class="lead">API pour accéder aux données des équipements et infrastructures publiques</p>
                </div>
                <div class="col-lg-4">
                    <div class="bg-white text-dark p-3 rounded shadow-sm">
                        <h6 class="fw-bold mb-2">Base URL</h6>
                        <code class="text-danger"><?= (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] ?></code>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container my-5">
        
        <!-- Endpoints GeoAbri -->
        <section class="mb-5">
            <div class="d-flex align-items-center mb-4">
                <div class="bg-primary" style="width: 4px; height: 2rem; margin-right: 1rem;"></div>
                <h2 class="fw-bold m-0">Endpoints GeoAbri</h2>
            </div>

            <!-- Equipements -->
            <div class="card endpoint-card mb-4 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge bg-success method-badge me-3">GET</span>
                        <code class="fs-5">/api/map/equipements</code>
                    </div>
                    <p class="text-muted mb-3">Récupère la liste des équipements dans une zone géographique donnée avec filtrage avancé.</p>
                    
                    <h6 class="fw-bold mb-2">Paramètres</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Paramètre</th>
                                    <th>Type</th>
                                    <th>Requis</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><code>minLat</code></td>
                                    <td>float</td>
                                    <td><span class="badge bg-danger">Oui</span></td>
                                    <td>Latitude minimale de la zone</td>
                                </tr>
                                <tr>
                                    <td><code>maxLat</code></td>
                                    <td>float</td>
                                    <td><span class="badge bg-danger">Oui</span></td>
                                    <td>Latitude maximale de la zone</td>
                                </tr>
                                <tr>
                                    <td><code>minLon</code></td>
                                    <td>float</td>
                                    <td><span class="badge bg-danger">Oui</span></td>
                                    <td>Longitude minimale de la zone</td>
                                </tr>
                                <tr>
                                    <td><code>maxLon</code></td>
                                    <td>float</td>
                                    <td><span class="badge bg-danger">Oui</span></td>
                                    <td>Longitude maximale de la zone</td>
                                </tr>
                                <tr>
                                    <td><code>category</code></td>
                                    <td>string</td>
                                    <td><span class="badge bg-secondary">Non</span></td>
                                    <td>Catégorie d'équipement (ex: Terrain de football)</td>
                                </tr>
                                <tr>
                                    <td><code>activites</code></td>
                                    <td>string</td>
                                    <td><span class="badge bg-secondary">Non</span></td>
                                    <td>Type d'activité pratiquée</td>
                                </tr>
                                <tr>
                                    <td><code>pmr</code></td>
                                    <td>string</td>
                                    <td><span class="badge bg-secondary">Non</span></td>
                                    <td>Accessibilité PMR (oui/non)</td>
                                </tr>
                                <tr>
                                    <td><code>etat</code></td>
                                    <td>string</td>
                                    <td><span class="badge bg-secondary">Non</span></td>
                                    <td>État (valide/attente)</td>
                                </tr>
                                <tr>
                                    <td><code>acces_libre</code></td>
                                    <td>string</td>
                                    <td><span class="badge bg-secondary">Non</span></td>
                                    <td>Accès libre (oui/non)</td>
                                </tr>
                                <tr>
                                    <td><code>commune</code></td>
                                    <td>string</td>
                                    <td><span class="badge bg-secondary">Non</span></td>
                                    <td>Nom de la commune</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h6 class="fw-bold mb-2 mt-3">Exemple de requête</h6>
                    <div class="code-block">
                        <span class="text-success">GET</span> /api/map/equipements?minLat=48.8&maxLat=48.9&minLon=2.3&maxLon=2.4&category=Terrain%20de%20football&pmr=oui
                    </div>

                    <h6 class="fw-bold mb-2 mt-3">Réponse</h6>
                    <div class="code-block">
{<br>
&nbsp;&nbsp;"equipements": [<br>
&nbsp;&nbsp;&nbsp;&nbsp;{<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"id": "123456",<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"lat": "48.8566",<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"lon": "2.3522"<br>
&nbsp;&nbsp;&nbsp;&nbsp;},<br>
&nbsp;&nbsp;&nbsp;&nbsp;...<br>
&nbsp;&nbsp;]<br>
}
                    </div>
                </div>
            </div>

            <!-- Suggestions -->
            <div class="card endpoint-card mb-4 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge bg-success method-badge me-3">GET</span>
                        <code class="fs-5">/api/map/suggestions</code>
                    </div>
                    <p class="text-muted mb-3">Recherche d'équipements par nom avec suggestions.</p>
                    
                    <h6 class="fw-bold mb-2">Paramètres</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Paramètre</th>
                                    <th>Type</th>
                                    <th>Requis</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><code>q</code></td>
                                    <td>string</td>
                                    <td><span class="badge bg-danger">Oui</span></td>
                                    <td>Terme de recherche</td>
                                </tr>
                                <tr>
                                    <td><code>minLat, maxLat, minLon, maxLon</code></td>
                                    <td>float</td>
                                    <td><span class="badge bg-secondary">Non</span></td>
                                    <td>Zone géographique pour limiter les résultats</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h6 class="fw-bold mb-2 mt-3">Exemple de requête</h6>
                    <div class="code-block">
                        <span class="text-success">GET</span> /api/map/suggestions?q=piscine
                    </div>

                    <h6 class="fw-bold mb-2 mt-3">Réponse</h6>
                    <div class="code-block">
[<br>
&nbsp;&nbsp;{<br>
&nbsp;&nbsp;&nbsp;&nbsp;"id": "789012",<br>
&nbsp;&nbsp;&nbsp;&nbsp;"name": "Piscine Municipale",<br>
&nbsp;&nbsp;&nbsp;&nbsp;"commune": "Paris",<br>
&nbsp;&nbsp;&nbsp;&nbsp;"lat": "48.8566",<br>
&nbsp;&nbsp;&nbsp;&nbsp;"lon": "2.3522",<br>
&nbsp;&nbsp;&nbsp;&nbsp;...<br>
&nbsp;&nbsp;},<br>
&nbsp;&nbsp;...<br>
]
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card endpoint-card mb-4 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge bg-success method-badge me-3">GET</span>
                        <code class="fs-5">/api/map/filters</code>
                    </div>
                    <p class="text-muted mb-3">Récupère les options disponibles pour les filtres (catégories et activités avec leurs compteurs).</p>
                    
                    <h6 class="fw-bold mb-2">Paramètres</h6>
                    <p class="text-muted">Aucun paramètre requis</p>

                    <h6 class="fw-bold mb-2 mt-3">Exemple de requête</h6>
                    <div class="code-block">
                        <span class="text-success">GET</span> /api/map/filters
                    </div>

                    <h6 class="fw-bold mb-2 mt-3">Réponse</h6>
                    <div class="code-block">
{<br>
&nbsp;&nbsp;"categories": [<br>
&nbsp;&nbsp;&nbsp;&nbsp;{ "category": "Terrain de football", "count": 245 },<br>
&nbsp;&nbsp;&nbsp;&nbsp;{ "category": "Piscine", "count": 89 },<br>
&nbsp;&nbsp;&nbsp;&nbsp;...<br>
&nbsp;&nbsp;],<br>
&nbsp;&nbsp;"activites": [<br>
&nbsp;&nbsp;&nbsp;&nbsp;{ "activite": "Football", "count": 312 },<br>
&nbsp;&nbsp;&nbsp;&nbsp;{ "activite": "Natation", "count": 156 },<br>
&nbsp;&nbsp;&nbsp;&nbsp;...<br>
&nbsp;&nbsp;]<br>
}
                    </div>
                </div>
            </div>
        </section>

        <!-- APIs Externes -->
        <section class="mb-5">
            <div class="d-flex align-items-center mb-4">
                <div class="bg-warning" style="width: 4px; height: 2rem; margin-right: 1rem;"></div>
                <h2 class="fw-bold m-0">APIs Externes Utilisées</h2>
            </div>

            <div class="alert alert-warning" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-triangle-fill me-2" viewBox="0 0 16 16"><path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/></svg>
                <strong>Note:</strong> Certaines APIs externes nécessitent une connexion <strong>HTTPS</strong>
            </div>

            <!-- Nominatim Search -->
            <div class="card endpoint-card mb-4 shadow-sm" style="border-left-color: #ffc107;">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge bg-success method-badge me-3">GET</span>
                        <code class="fs-6">nominatim.openstreetmap.org/search</code>
                    </div>
                    <p class="text-muted mb-3">Recherche de villes et codes postaux. Utilisé pour la barre de recherche.</p>
                    
                    <h6 class="fw-bold mb-2 mt-3">Exemple de requête</h6>
                    <div class="code-block">
                        <span class="text-success">GET</span> https://nominatim.openstreetmap.org/search?format=json&q=Paris
                    </div>
                </div>
            </div>

            <!-- Nominatim Lookup -->
            <div class="card endpoint-card mb-4 shadow-sm" style="border-left-color: #ffc107;">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge bg-success method-badge me-3">GET</span>
                        <code class="fs-6">nominatim.openstreetmap.org/lookup</code>
                    </div>
                    <p class="text-muted mb-3">Récupération des données géographiques d'une ville (polygones GeoJSON). Utilisé pour dessiner les limites des villes sur la carte.</p>
                    
                    <h6 class="fw-bold mb-2 mt-3">Exemple de requête</h6>
                    <div class="code-block">
                        <span class="text-success">GET</span> https://nominatim.openstreetmap.org/lookup?format=json&polygon_geojson=1&osm_ids=R7444
                    </div>
                </div>
            </div>

            <!-- Wikimedia Commons -->
            <div class="card endpoint-card mb-4 shadow-sm" style="border-left-color: #ffc107;">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge bg-success method-badge me-3">GET</span>
                        <code class="fs-6">commons.wikimedia.org/w/api.php</code>
                    </div>
                    <p class="text-muted mb-3">Recherche d'images d'équipements. Utilisé pour afficher des photos des installations.</p>
                    
                    <h6 class="fw-bold mb-2 mt-3">Exemple de requête</h6>
                    <div class="code-block text-break">
                        <span class="text-success">GET</span> https://commons.wikimedia.org/w/api.php?action=query&format=json&generator=search&gsrsearch="Stade%20de%20France"&gsrnamespace=6&gsrlimit=1&prop=imageinfo&iiprop=url&origin=*
                    </div>
                </div>
            </div>
        </section>

        <!-- Informations supplémentaires -->
        <section class="mb-5">
            <div class="row">
                <div class="col-md-6">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-shield-check me-2" viewBox="0 0 16 16"><path d="M5.338 1.59a61 61 0 0 0-2.837.856.48.48 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.7 10.7 0 0 0 2.287 2.233c.346.244.652.42.893.533q.18.085.293.118a1 1 0 0 0 .101.025 1 1 0 0 0 .1-.025q.114-.034.294-.118c.24-.113.547-.29.893-.533a10.7 10.7 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.8 11.8 0 0 1-2.517 2.453 7 7 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7 7 0 0 1-1.048-.625 11.8 11.8 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 63 63 0 0 1 5.072.56"/><path d="M10.854 5.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0"/></svg>
                                Cache & Performance
                            </h5>
                            <p class="card-text text-muted">
                                Les réponses de l'API <code>/api/map/filters</code> sont mises en cache pendant 1 heure (3600s) pour optimiser les performances.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-globe me-2" viewBox="0 0 16 16"><path d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m7.5-6.923c-.67.204-1.335.82-1.887 1.855A8 8 0 0 0 5.145 4H7.5zM4.09 4a9.3 9.3 0 0 1 .64-1.539 7 7 0 0 1 .597-.933A7.03 7.03 0 0 0 2.255 4zm-.582 3.5c.03-.877.138-1.718.312-2.5H1.674a7 7 0 0 0-.656 2.5zM4.847 5a12.5 12.5 0 0 0-.338 2.5H7.5V5zM8.5 5v2.5h3.99a12.5 12.5 0 0 0-.337-2.5zM4.51 8.5a12.5 12.5 0 0 0 .337 2.5H7.5V8.5zm3.99 0V11h2.653c.187-.765.306-1.608.338-2.5zM5.145 12q.208.58.468 1.068c.552 1.035 1.218 1.65 1.887 1.855V12zm.182 2.472a7 7 0 0 1-.597-.933A9.3 9.3 0 0 1 4.09 12H2.255a7 7 0 0 0 3.072 2.472M3.82 11a13.7 13.7 0 0 1-.312-2.5h-2.49c.062.89.291 1.733.656 2.5zm6.853 3.472A7 7 0 0 0 13.745 12H11.91a9.3 9.3 0 0 1-.64 1.539 7 7 0 0 1-.597.933M8.5 12v2.923c.67-.204 1.335-.82 1.887-1.855q.26-.487.468-1.068zm3.68-1h2.146c.365-.767.594-1.61.656-2.5h-2.49a13.7 13.7 0 0 1-.312 2.5m2.802-3.5a7 7 0 0 0-.656-2.5H12.18c.174.782.282 1.623.312 2.5zM11.27 2.461c.247.464.462.98.64 1.539h1.835a7 7 0 0 0-3.072-2.472c.218.284.418.598.597.933M10.855 4a8 8 0 0 0-.468-1.068C9.835 1.897 9.17 1.282 8.5 1.077V4z"/></svg>
                                Format de réponse
                            </h5>
                            <p class="card-text text-muted">
                                Toutes les réponses sont au format <strong>JSON</strong> avec l'en-tête <code>Content-Type: application/json</code>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>

    <?php require_once __DIR__ . '/../Components/footer.php'; ?>

</body>
</html>