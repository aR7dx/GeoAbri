<?php 
include_once './utils/header.php';
$titre = setPageTitle('Accueil');

if (isset($_GET) && sizeof($_GET) > 1) {
    $ville = $_GET['ville'];
    $type_equipement = $_GET['type_equipement'];
    $accessibilite_pmr = $_GET['accessibilite_pmr'];
    $type_de_sol = $_GET['type_de_sol'];
}
?>


<!DOCTYPE html>
<html lang="fr">

<?php include_once $meta; ?>

<body>
    <?php include_once $navbar; ?>

    <?php 
        if (!isset($_SESSION['notif_conn_db_shown'])) {
            if (isset($conn)) {
                echo Notification::notification_success('La connexion à la base de données a réussi.');
                $_SESSION['notif_conn_db_shown'] = true;
            } else {
                echo Notification::notification_error('La connexion à la base de données a échoué.');
            }
        }

        if (isset($_GET) && sizeof($_GET) > 0) {
            echo Notification::notification_success('Formulaire envoyé');
        }
    ?>

    <main class="container">
        <div class="px-4 py-5 my-5 text-center bg-light">
            <h1 class="display-5 fw-bold text-body-emphasis">Trouvez votre équipement sportif</h1>
            
            <div class="col-lg-6 mx-auto">
                <p class="lead mb-4">Recherchez parmi les équipements sportifs publics en France par localisation et caractéristiques</p>
            </div>
        </div>

        <div class="card mb-3 p-3">
            <form method="GET" class="d-flex form align-items-center">
                <div class="d-flex flex-column form-control border-0 gap-2">
                    <label><strong>Ville ou Code Postal</strong></label>
                    <input name="ville" type="text" class="input-group-text" placeholder="Caen, 14001..." value="<?= isset($ville) ? htmlspecialchars($ville) : ''; ?>" style="text-align:left;"/>
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
                        <?php 
                            if (!isset($conn)) {
                                echo '<div class="alert alert-danger">Connexion impossible.</div>';
                            }
                            else if (!isset($ville) && !isset($type_equipement) && !isset($accessibilite_pmr) && !isset($type_de_sol)) {
                                echo '<div class="alert alert-info">Faites une recherche !</div>';
                            }
                            else {

                                $donnees = getEquipementsWithParameters($conn, $_GET['ville'], $type_equipement, $accessibilite_pmr, $type_de_sol);

                                if (!isset($donnees)) {
                                    echo '<div class="alert alert-danger">Problème lors de la recherche</div>';
                                } else {
                                    foreach($donnees as $equipement) {
                                        echo '
                                            
                                        ';
                                    }
                                }
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </main>


    <?php include_once './utils/footer.php'; ?>
</body>
</html>