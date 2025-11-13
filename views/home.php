<!DOCTYPE html>
<html lang="fr">

<?php require_once dirname(__DIR__) . '/components/meta.php'; ?>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<link rel="stylesheet" href="/assets/css/leafletMap.css"/>
<script defer src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script defer src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script defer src="/assets/js/leafletMap.js"></script>

<body>

    <?php require_once 'navbar.php'; ?>
    
    <main class="container">
        <div class="px-4 py-5 my-5 text-center bg-light">
            <h1 class="display-5 fw-bold text-body-emphasis">Trouvez votre équipement</h1>
            
            <div class="col-lg-6 mx-auto">
                <p class="lead mb-4">Recherchez parmi les équipements publics en France par localisation et caractéristiques</p>
            </div>

            <div id="geolocateMe">
                <button class="btn btn-primary" href="#map">Me localiser</button>
            </div>
        </div>

        <div id="filter_section" class="card mb-3 p-3">
            <form method="GET" class="d-flex form align-items-center">
                <div class="d-flex flex-column form-control border-0 gap-2">
                    <label><strong>Ville ou Code Postal</strong></label>
                    <input name="ville" type="text" class="input-group-text" placeholder="Paris, 75001..." value="<?= isset($ville) ? htmlspecialchars($ville) : ''; ?>" style="text-align:left;"/>
                </div>
                <div class="d-flex flex-column form-control border-0 gap-2">
                    <label><strong>Type d'équipement</strong></label>
                    <select name="type_equipement" class="form-select">
                        <option value="tous" <?= (isset($type_equipement) && $type_equipement == 'tous') ? 'selected' : ''; ?>>Tous</option>
                        <option value="terrain" <?= (isset($type_equipement) && $type_equipement == 'terrain') ? 'selected' : ''; ?>>Terrain</option>
                        <option value="gymnase" <?= (isset($type_equipement) && $type_equipement == 'gymnase') ? 'selected' : ''; ?>>Gymnase</option>
                        <option value="piscine" <?= (isset($type_equipement) && $type_equipement == 'piscine') ? 'selected' : ''; ?>>Piscine</option>
                        <option value="court" <?= (isset($type_equipement) && $type_equipement == 'court') ? 'selected' : ''; ?>>Court</option>
                        <option value="stade" <?= (isset($type_equipement) && $type_equipement == 'stade') ? 'selected' : ''; ?>>Stade</option>
                    </select>
                </div>
                <div class="d-flex flex-column form-control border-0 gap-2">
                    <label><strong>Accessibilité PMR</strong></label>
                    <select name="accessibilite_pmr" class="form-select">
                        <option value="tous" <?= (isset($accessibilite_pmr) && $accessibilite_pmr == 'tous') ? 'selected' : ''; ?>>Tous</option>
                        <option value="oui" <?= (isset($accessibilite_pmr) && $accessibilite_pmr == 'Oui') ? 'selected' : ''; ?>>Oui</option>
                        <option value="non" <?= (isset($accessibilite_pmr) && $accessibilite_pmr == 'Non') ? 'selected' : ''; ?>>Non</option>
                    </select>
                </div>
                <div class="d-flex flex-column form-control border-0 gap-2">
                    <label><strong>Type de sol</strong></label>
                    <select name="type_de_sol" class="form-select">
                        <option value="tous" <?= (isset($type_de_sol) && $type_de_sol == 'tous') ? 'selected' : ''; ?>>Tous</option>
                        <option value="gazon" <?= (isset($type_de_sol) && $type_de_sol == 'Gazon') ? 'selected' : ''; ?>>Gazon</option>
                        <option value="sable" <?= (isset($type_de_sol) && $type_de_sol == 'Sable') ? 'selected' : ''; ?>>Sable</option>
                        <option value="synthétique" <?= (isset($type_de_sol) && $type_de_sol == 'Synthétique') ? 'selected' : ''; ?>>Synthétique</option>
                        <option value="bitume" <?= (isset($type_de_sol) && $type_de_sol == 'Bitume') ? 'selected' : ''; ?>>Bitume</option>
                    </select>
                </div>
                
                <div class="d-flex justify-content-start mt-4">
                    <button type="submit" class="btn btn-primary">Rechercher</button>
                </div>
            </form>
        </div>


        <div class="container card p-4">
            <div class="row">
                <div class="col">
                    <div class="d-flex flex-column gap-2">
                        <span><strong>Carte Interactive</strong></span>
                        <div id="map"></div>
                    </div>
                </div>
                <div class="col">
                    <div class="d-flex flex-column gap-2">
                        <span><strong>Résultat</strong></span>
                        <div class="d-flex flex-column gap-2" style="height: 550px; overflow: scroll;">
                            <!-- TODO -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

</body>
</html>