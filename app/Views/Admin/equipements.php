<?php 
    use App\Views\Components\Modal;
    use App\Views\Components\Notification;
?>

<!DOCTYPE html>
<html lang="fr">

<?php require_once dirname(dirname(__DIR__)) . '/Views/Includes/meta.php'; ?>

<link rel="stylesheet" href="/public/css/dashboard.css"/>

<script defer src="/public/js/Dashboard/equipements.js"></script>

<body>

    <?php 
    if (isset($_SESSION['notification']['not_your_equipement']) && $_SESSION['notification']['not_your_equipement'] === 1) {
        
        $_SESSION['notification']['not_your_equipement'] = 0;
        unset($_SESSION['notification']['not_your_equipement']);
        echo Notification::notification_error("Vous ne pouvez pas faire cette action car cet équipement n'est pas le votre.");
    }
    ?>

    <?php require_once __DIR__ . './../Components/dashboard-header.php'; ?>
    <?php require_once __DIR__ . './../Components/dashboard-sidebar.php'; ?>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Gestion des équipements</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <button id="add-equipement" type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addEquipementModal">Ajouter un équipement</button>
            </div>
        </div>

        <!-- modal pour ajouter un equipement -->
        <form method="POST">
            <div id="addEquipementModal" class="modal fade" tabindex="-1" aria-labelledby="addEquipementModal" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5 fw-bold text-primary">Ajouter un equipement</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body d-flex flex-column gap-3">

                        
                            <div class="">
                                <label for="new-equipement-name" class="form-label">Nom</label>
                                <input type="text" class="form-control" name="name" id="new-equipement-name" placeholder="Nom" required autofocus>
                            </div>
                            <div class="">
                                <label for="new-equipement-city" class="form-label">Ville</label>
                                <input type="text" class="form-control" name="commune" id="new-equipement-city" placeholder="Ville" required>
                            </div>
                            <div class="">
                                <label for="new-equipement-postcode" class="form-label">Code postal</label>
                                <input type="text" class="form-control" name="code_postal" id="new-equipement-postcode" placeholder="Code postal" required>
                            </div>
                            <div class="">
                                <label for="new-equipement-address" class="form-label">Adresse</label>
                                <input type="text" class="form-control" name="adresse" id="new-equipement-address" placeholder="Adresse" required>
                            </div>
                            <div class="">
                                <label for="new-equipement-description" class="form-label">Description</label>
                                <textarea class="form-control" name="description" id="new-equipement-description" rows="3" required></textarea>
                            </div>


                        </div>
                        <div class="modal-footer border-0">
                            <button type="submit" name="add" class="btn btn-primary" value="1">Ajouter</button>
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="card-title mb-0">Equipements</h5>
                    </div>
                    <div class="col-auto">
                        <div class="d-flex gap-2">
                            <!-- Search -->
                            <div class="form-outline mb-2">
                                <input type="search" id="search-input" class="form-control" placeholder="Rechercher un équipement..." aria-label="Search" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">

                <!-- Table -->
                <div class="table-responsive">
                    <table id="equipements-table" class="table table-hover mb-0">
                        <thead class="table-light w-100">
                            <tr>
                                <th>ID</th>
                                <th>NOM</th>
                                <th>TYPE</th>
                                <th>ADRESSE</th>
                                <th>PROPRIETAIRE</th>
                                <th class="d-flex justify-content-center">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center p-3">
                    <div class="text-muted">
                        <span id="nb-equipements-results"></span>
                    </div>
                    <nav>
                        <ul id="equipements-pagination" class="pagination pagination-sm mb-0"></ul>
                    </nav>
                </div>
            </div>
        </div>


        <!-- modal pour la suppression d'un equipement -->
        <?= Modal::modal_deletion("deleteEquipementModal", "Vous êtes sur le point de supprimer définitivement cet équipement et toutes les données associées."); ?>
    </main>

</body>
</html>