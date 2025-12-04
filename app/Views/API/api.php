<!DOCTYPE html>
<html lang="fr">

<?php require_once dirname(dirname(__DIR__)) . '/Views/Includes/meta.php'; ?>

<body>

    <?php require_once __DIR__ . './../Components/navbar.php'; ?>

    <div class="container d-flex flex-column mt-5 gap-2">
        <div class="d-flex flex-column">
            <div class="d-flex flex-column gap-0 mb-5 bg-light p-4 rounded">
                <h2 class="m-0">GeoAbri-API</h2>
                <span>API de l'application web GeoAbri.</span>
            </div>


            <div class="d-flex flex-column gap-3 mb-5">
                <h3>Equipements</h3>
                <div class="card p-3 d-flex flex-row gap-2 align-items-center alert alert-primary">
                    <div class="card p-1 px-2 bg-primary text-white" style="min-width: 48px;">GET</div>
                    <span class="p-2 d-block overflow-auto text-nowrap"><strong>/api/map/equipements</strong>?minLat=<strong>{minLat}</strong>&maxLat=<strong>{maxLat}</strong>&minLon=<strong>{minLon}</strong>&maxLon=<strong>{maxLon}</strong></span>
                </div>

                <h3>Suggestions</h3>
                <span class="card p-3 d-flex flex-row gap-2 align-items-center alert alert-primary">
                    <div class="card p-1 px-2 bg-primary text-white" style="min-width: 48px;">GET</div>
                    <span class="p-2 d-block overflow-auto text-nowrap"><strong>/api/map/suggestions</strong>?q=<strong>{query}</strong></span>
                </span>
                <span class="card p-3 d-flex flex-row gap-2 align-items-center alert alert-primary">
                    <div class="card p-1 px-2 bg-primary text-white" style="min-width: 48px;">GET</div>
                    <span class="p-2 d-block overflow-auto text-nowrap"><strong>/api/map/suggestions</strong>?q=<strong>{query}</strong>&page=<strong>{page}</strong></span>
                </span>
            </div>
        </div>
        
        <div class="d-flex flex-column">
            <div class="d-flex flex-column gap-0 mb-5 bg-light p-4 rounded">
                <h2 class="m-0">API externes utilisées par Geoabri</h2>
                <span>Attention certaine API ci-dessous necessitent d'être appellée depuis une url <strong>https</strong>.</span>
            </div>

            <div class="d-flex flex-column gap-3 mb-5">

                <div class="d-flex flex-column gap-2">
                    <div class="d-flex flex-column">
                        <h3>Nominatim (Search)</h3>
                        <span>Cette api est utilisée pour trouver les villes lors de la recherche ou trouver une ville par son code postal.</span>
                    </div>

                    <div class="card p-3 d-flex flex-row gap-2 align-items-center alert alert-primary">
                        <div class="card p-1 px-2 bg-primary text-white" style="min-width: 48px;">GET</div>
                        <span class="p-2 d-block overflow-auto text-nowrap">https://nominatim.openstreetmap.org/search?format=json&q=<strong>{query}</strong></span>
                    </div>
                </div>

                
                <div class="d-flex flex-column gap-2">
                    <div class="d-flex flex-column">
                        <h3>Nominatim (Lookup)</h3>
                        <span>Cette api est utilisée pour trouver les informations geographique d'une ville, cela est notamment utilse pour la représentation graphique des villes.</span>
                    </div>

                    <span class="card p-3 d-flex flex-row gap-2 align-items-center alert alert-primary">
                        <div class="card p-1 px-2 bg-primary text-white" style="min-width: 48px;">GET</div>
                        <span class="p-2 d-block overflow-auto text-nowrap">https://nominatim.openstreetmap.org/lookup?format=json&polygon_geojson=1&osm_ids=<strong>{osmType}</strong><strong>{osmId}</strong></span>
                    </span>
                </div>
            </div>
        </div>

    </div>

    <?php require_once __DIR__ . '/../Components/footer.php'; ?>

</body>
</html>