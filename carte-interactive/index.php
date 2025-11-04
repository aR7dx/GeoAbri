<?php 
include_once '../utils/header.php';
$titre = setPageTitle('Carte Interative');
?>


<!DOCTYPE html>
<html lang="fr">

<?php include_once $meta; ?>

<body>
    <?php include_once $navbar; ?>

    <main class="container">

        <div class="mb-2">
            <form method="GET" class="d-flex form align-items-center">

                <div class="d-flex flex-column form-control border-0 gap-2 p-0 pe-2">
                    <label><strong>Ville ou Code Postal</strong></label>
                    <input name="ville" type="text" class="input-group-text" placeholder="Caen, 14001..." value="<?= isset($_GET['ville']) ? htmlspecialchars($_GET['ville']) : ''; ?>" style="text-align:left;"/>
                </div>
                <div class="d-flex flex-column form-control border-0 gap-2 p-0 pe-2">
                    <label><strong>Type d'équipement</strong></label>
                    <select name="type_equipement" class="form-select">
                        <option value="terrain" <?= (isset($_GET['type_equipement']) && $_GET['type_equipement'] == 'terrain') ? 'selected' : ''; ?>>Terrain</option>
                        <option value="gymnase" <?= (isset($_GET['type_equipement']) && $_GET['type_equipement'] == 'gymnase') ? 'selected' : ''; ?>>Gymnase</option>
                        <option value="piscine" <?= (isset($_GET['type_equipement']) && $_GET['type_equipement'] == 'piscine') ? 'selected' : ''; ?>>Piscine</option>
                        <option value="court" <?= (isset($_GET['type_equipement']) && $_GET['type_equipement'] == 'court') ? 'selected' : ''; ?>>Court</option>
                        <option value="stade" <?= (isset($_GET['type_equipement']) && $_GET['type_equipement'] == 'stade') ? 'selected' : ''; ?>>Stade</option>
                    </select>
                </div>
                <div class="d-flex flex-column form-control border-0 gap-2 p-0 pe-2">
                    <label><strong>Accessibilité PMR</strong></label>
                    <select name="accessibilite_pmr" class="form-select">
                        <option <?= (isset($_GET['accessibilite_pmr']) && $_GET['accessibilite_pmr'] == 'Oui') ? 'selected' : ''; ?>>Oui</option>
                        <option <?= (isset($_GET['accessibilite_pmr']) && $_GET['accessibilite_pmr'] == 'Non') ? 'selected' : ''; ?>>Non</option>
                    </select>
                </div>
                <div class="d-flex flex-column form-control border-0 gap-2 p-0 pe-2">
                    <label><strong>Type de sol</strong></label>
                    <select name="type_de_sol" class="form-select">
                        <option <?= (isset($_GET['type_de_sol']) && $_GET['type_de_sol'] == 'Gazon') ? 'selected' : ''; ?>>Gazon</option>
                        <option <?= (isset($_GET['type_de_sol']) && $_GET['type_de_sol'] == 'Sable') ? 'selected' : ''; ?>>Sable</option>
                        <option <?= (isset($_GET['type_de_sol']) && $_GET['type_de_sol'] == 'Synthétique') ? 'selected' : ''; ?>>Synthétique</option>
                        <option <?= (isset($_GET['type_de_sol']) && $_GET['type_de_sol'] == 'Bitume') ? 'selected' : ''; ?>>Bitume</option>
                    </select>
                </div>
                
                <div class="d-flex justify-content-start mt-4">
                    <button type="submit" class="btn btn-primary">Appliquer les filtres</button>
                </div>
            </form>
        </div>

        <div class="d-flex mb-3 gap-2">
            <div class="card py-3 px-3 w-100">
                <label for="totalEquipements">Total des équipement</label>
                <span id="totalEquipements" class="importantData" style="color: #e68506ff;"><strong>0</strong></span>
            </div>
            <div class="card py-3 px-3 w-100">
                <label for="accessiblesPMR">Accessibles PMR</label>
                <span id="accessiblesPMR" class="importantData" style="color: #095f09ff;"><strong>0</strong></span>
            </div>
            <div class="card py-3 px-3 w-100">
                <label for="typesDifferents">Types différents</label>
                <span id="typesDifferents" class="importantData"><strong>0</strong></span>
            </div>
        </div>

        <div class="container card p-2">
            <div id="map"></div>
        </div>
    </main>


    <?php include_once '../utils/footer.php'; ?>
</body>
</html>