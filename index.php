<?php 
include_once './utils/header.php';
$titre = "Accueil - Équipements d'urgences";
?>


<!DOCTYPE html>
<html lang="fr">

<?php include_once $meta; ?>

<body>
    <?php
        include_once $navbar;
    ?>

    <main class="container">
        <div class="px-4 py-5 my-5 text-center bg-light">
        <h1 class="display-5 fw-bold text-body-emphasis">Trouvez votre equipement sportif</h1>
        
        <div class="col-lg-6 mx-auto">
            <p class="lead mb-4">Recherchez parmi les équipements sportifs publics en France par localisation et caractéristiques</p>
        </div>
        </div>

        <div class="card mb-5 p-3">
            <form method="GET" class="d-flex form">
                <div class="d-flex flex-column form-control border-0">
                    <label><strong>Ville ou Code Postal</strong></label>
                    <input name="ville" type="text" class="input-group-text" placeholder="Caen" value="<?= isset($_GET['ville']) ? htmlspecialchars($_GET['ville']) : ''; ?>" style="text-align:left;"/>
                </div>
                <div class="d-flex flex-column form-control border-0">
                    <label><strong>Type d'équipement</strong></label>
                    <select name="type_equipement" class="form-select">
                        <option value="terrain" <?= (isset($_GET['type_equipement']) && $_GET['type_equipement'] == 'terrain') ? 'selected' : ''; ?>>Terrain</option>
                        <option value="gymnase" <?= (isset($_GET['type_equipement']) && $_GET['type_equipement'] == 'gymnase') ? 'selected' : ''; ?>>Gymnase</option>
                        <option value="piscine" <?= (isset($_GET['type_equipement']) && $_GET['type_equipement'] == 'piscine') ? 'selected' : ''; ?>>Piscine</option>
                        <option value="court" <?= (isset($_GET['type_equipement']) && $_GET['type_equipement'] == 'court') ? 'selected' : ''; ?>>Court</option>
                        <option value="stade" <?= (isset($_GET['type_equipement']) && $_GET['type_equipement'] == 'stade') ? 'selected' : ''; ?>>Stade</option>
                    </select>
                </div>
                <div class="d-flex flex-column form-control border-0">
                    <label><strong>Accessibilité PMR</strong></label>
                    <select name="accessibilite_pmr" class="form-select">
                        <option <?= (isset($_GET['accessibilite_pmr']) && $_GET['accessibilite_pmr'] == 'Oui') ? 'selected' : ''; ?>>Oui</option>
                        <option <?= (isset($_GET['accessibilite_pmr']) && $_GET['accessibilite_pmr'] == 'Non') ? 'selected' : ''; ?>>Non</option>
                    </select>
                </div>
                <div class="d-flex flex-column form-control border-0">
                    <label><strong>Type de sol</strong></label>
                    <select name="type_de_sol" class="form-select">
                        <option <?= (isset($_GET['type_de_sol']) && $_GET['type_de_sol'] == 'Gazon') ? 'selected' : ''; ?>>Gazon</option>
                        <option <?= (isset($_GET['type_de_sol']) && $_GET['type_de_sol'] == 'Sable') ? 'selected' : ''; ?>>Sable</option>
                        <option <?= (isset($_GET['type_de_sol']) && $_GET['type_de_sol'] == 'Synthétique') ? 'selected' : ''; ?>>Synthétique</option>
                        <option <?= (isset($_GET['type_de_sol']) && $_GET['type_de_sol'] == 'Bitume') ? 'selected' : ''; ?>>Bitume</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary">Rechercher</button>
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
                        <div class="alert alert-danger">Aucun résultat</div>
                    </div>
                </div>
            </div>
        </div>
    </main>


    <?php 
        include_once './utils/footer.php';
    ?>
</body>
</html>