<!DOCTYPE html>
<html lang="fr">

<?php require_once dirname(dirname(__DIR__)) . '/views/Includes/meta.php'; ?>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<link rel="stylesheet" href="/public/css/leafletMap.css"/>
<script defer src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script defer src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script defer src="/public/js/leafletMap.js"></script>

<body>

    <?php require_once __DIR__ . './../Components/navbar.php'; ?>

    <main class="d-flex">
        <div class="d-flex flex-column flex-shrink-0 p-3 bg-light" style="width: 280px;">
            <span class="fs-5 fw-semibold ps-3">Recherche</span>
            <hr>

            <!-- Stats -->
             <!--
            <div class="d-flex flex-column mb-3 gap-2">
                <div class="card py-3 px-3 w-100">
                    <label for="totalEquipements">Total des équipement</label>
                    <span id="totalEquipements" class="importantData" style="color: #e68506ff;"><strong><?= 0 ?></strong></span>
                </div>
                <div class="card py-3 px-3 w-100">
                    <label for="accessiblesPMR">Accessibles PMR</label>
                    <span id="accessiblesPMR" class="importantData" style="color: #095f09ff;"><strong><?= 0 ?></strong></span>
                </div>
                <div class="card py-3 px-3 w-100">
                    <label for="typesDifferents">Types différents</label>
                    <span id="typesDifferents" class="importantData"><strong><?= 0 ?></strong></span>
                </div>
            </div>
-->

            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="#" class="nav-link active" aria-current="page">
                    <svg class="bi me-2" width="16" height="16"><use xlink:href="#test1"></use></svg>
                    Button 1
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link link-dark">
                    <svg class="bi me-2" width="16" height="16"><use xlink:href="#test2"></use></svg>
                    Button 2
                    </a>
                </li>
            </ul>
        </div>


        <!-- Leaflet Map -->
        <div id="map" class="resize-map"></div>
    </main>

</body>
</html>