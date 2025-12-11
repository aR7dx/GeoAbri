<?php 
    use App\Views\Components\Modal;
    use App\Views\Components\Notification;
?>

<!DOCTYPE html>
<html lang="fr">

<?php require_once dirname(dirname(__DIR__)) . '/Views/Includes/meta.php'; ?>

<link rel="stylesheet" href="/public/css/dashboard.css"/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<script defer src="/public/js/Dashboard/equipements.js"></script>
<script defer src="/public/js/Dashboard/equipement-form.js"></script>

<body>

    <?php 
    if (isset($_SESSION['notification']['not_your_equipement']) && $_SESSION['notification']['not_your_equipement'] === 1) {
        $_SESSION['notification']['not_your_equipement'] = 0;
        unset($_SESSION['notification']['not_your_equipement']);
        echo Notification::notification_error("Vous ne pouvez pas faire cette action car cet équipement n'est pas le votre.");
    }
    
    if (isset($_SESSION['notification']['equipement_add_success']) && $_SESSION['notification']['equipement_add_success'] === 1) {
        $_SESSION['notification']['equipement_add_success'] = 0;
        unset($_SESSION['notification']['equipement_add_success']);
        echo Notification::notification_success("L'équipement a été ajouté avec succès.");
    }
    
    if (isset($_SESSION['notification']['equipement_add_error']) && $_SESSION['notification']['equipement_add_error'] === 1) {
        $_SESSION['notification']['equipement_add_error'] = 0;
        unset($_SESSION['notification']['equipement_add_error']);
        echo Notification::notification_error("Erreur lors de l'ajout de l'équipement. Veuillez réessayer.");
    }
    ?>

    <?php require_once __DIR__ . './../Components/dashboard-header.php'; ?>
    <?php require_once __DIR__ . './../Components/dashboard-sidebar.php'; ?>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
            <h1 class="h2">Gestion des équipements</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <button id="add-equipement" type="button" class="btn btn-sm btn-success d-flex flex-row gap-1" data-bs-toggle="modal" data-bs-target="#addEquipementModal">
                    <i class="bi bi-plus-circle text-white fw-bold"></i>    
                    Ajouter un équipement
                </button>
            </div>
        </div>

        <!-- modal pour ajouter un equipement -->
        <form method="POST">
            <div id="addEquipementModal" class="modal fade" tabindex="-1" aria-labelledby="addEquipementModal" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">
                                <i class="bi bi-plus-circle-fill me-2"></i>
                                Nouvel équipement
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        
                        <ul class="nav nav-tabs px-3 pt-3 bg-light" id="equipementTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active text-dark" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">
                                    <i class="bi bi-info-circle me-1 text-dark"></i> Général
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link text-dark" id="localisation-tab" data-bs-toggle="tab" data-bs-target="#localisation" type="button" role="tab">
                                    <i class="bi bi-geo-alt me-1 text-dark"></i> Localisation
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link text-dark" id="technique-tab" data-bs-toggle="tab" data-bs-target="#technique" type="button" role="tab">
                                    <i class="bi bi-tools me-1 text-dark"></i> Technique
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link text-dark" id="equipements-tab" data-bs-toggle="tab" data-bs-target="#equipements" type="button" role="tab">
                                    <i class="bi bi-check2-square me-1 text-dark"></i> Équipements
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link text-dark" id="finaliser-tab" data-bs-toggle="tab" data-bs-target="#finaliser" type="button" role="tab">
                                    <i class="bi bi-file-earmark-check me-1 text-dark"></i> Finaliser
                                </button>
                            </li>
                        </ul>

                        <div class="modal-body">
                            <div class="tab-content" id="equipementTabContent">
                                
                                <!-- Informations générales -->
                                <div class="tab-pane fade show active" id="general" role="tabpanel">
                                    <div class="card mb-3">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Informations générales</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label for="new-equipement-name" class="form-label">Nom <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="nom" id="new-equipement-name" placeholder="Nom de l'équipement" required autofocus>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="new-equipement-type" class="form-label">Type <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="type" id="new-equipement-type" placeholder="Ex: Court de tennis, Piscine" required>
                                                </div>
                                                <div class="col-md-12">
                                                    <label for="new-equipement-description" class="form-label">Description <span class="text-danger">*</span></label>
                                                    <textarea class="form-control" name="description" id="new-equipement-description" rows="3" placeholder="Décrivez l'équipement..." required></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Localisation -->
                                <div class="tab-pane fade" id="localisation" role="tabpanel">
                                    <div class="card mb-3">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0"><i class="bi bi-geo-alt me-2"></i>Localisation</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label for="new-equipement-city" class="form-label">Commune <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="commune" id="new-equipement-city" placeholder="Ville" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="new-equipement-postcode" class="form-label">Code postal <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="code_postal" id="new-equipement-postcode" placeholder="Code postal" required>
                                                </div>
                                                <div class="col-md-12">
                                                    <label for="new-equipement-address" class="form-label">Adresse <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="adresse" id="new-equipement-address" placeholder="Adresse complète" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Caractéristiques techniques -->
                                <div class="tab-pane fade" id="technique" role="tabpanel">
                                    <div class="card mb-3">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0"><i class="bi bi-tools me-2"></i>Caractéristiques techniques</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label for="new-equipement-nature" class="form-label">Nature</label>
                                                    <select class="form-select" name="nature" id="new-equipement-nature">
                                                        <option value="">-- Sélectionner --</option>
                                                        <option value="Découvert">Découvert</option>
                                                        <option value="Couvert">Couvert</option>
                                                        <option value="Semi-couvert">Semi-couvert</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="new-equipement-sol" class="form-label">Nature du sol</label>
                                                    <input type="text" class="form-control" name="aire_nature_sol" id="new-equipement-sol" placeholder="Ex: Béton, Gazon, Parquet">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="new-equipement-chauffage" class="form-label">Énergie de chauffage</label>
                                                    <select class="form-select" name="chauffage_energie" id="new-equipement-chauffage">
                                                        <option value="">-- Sélectionner --</option>
                                                        <option value="Électricité">Électricité</option>
                                                        <option value="Gaz">Gaz</option>
                                                        <option value="Fioul">Fioul</option>
                                                        <option value="Bois">Bois</option>
                                                        <option value="Aucun">Aucun</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card mb-3">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0"><i class="bi bi-rulers me-2"></i>Dimensions</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <label for="new-equipement-longueur" class="form-label">Longueur (m)</label>
                                                    <input type="number" step="0.1" class="form-control" name="aire_longueur" id="new-equipement-longueur">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="new-equipement-largeur" class="form-label">Largeur (m)</label>
                                                    <input type="number" step="0.1" class="form-control" name="aire_largeur" id="new-equipement-largeur">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="new-equipement-hauteur" class="form-label">Hauteur (m)</label>
                                                    <input type="number" step="0.1" class="form-control" name="aire_hauteur" id="new-equipement-hauteur">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="new-equipement-surface" class="form-label">Surface (m²)</label>
                                                    <input type="number" step="0.1" class="form-control" name="aire_surface" id="new-equipement-surface">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Équipements et commodités -->
                                <div class="tab-pane fade" id="equipements" role="tabpanel">
                                    <div class="card mb-3">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0"><i class="bi bi-check2-square me-2"></i>Équipements et commodités</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label for="new-equipement-vestiaires" class="form-label">Vestiaires sportifs</label>
                                                    <input type="number" step="0.1" class="form-control" name="vestiaires_sportifs_nb" id="new-equipement-vestiaires">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="new-equipement-vestiaires-arbitres" class="form-label">Vestiaires arbitres</label>
                                                    <input type="number" step="0.1" class="form-control" name="vestiaires_arbitres_nb" id="new-equipement-vestiaires-arbitres">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="new-equipement-tribunes" class="form-label">Places en tribune</label>
                                                    <input type="number" step="0.1" class="form-control" name="places_tibune_nb" id="new-equipement-tribunes">
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" name="aire_eclairage" value="Oui" id="new-equipement-eclairage">
                                                        <label class="form-check-label" for="new-equipement-eclairage">Éclairage</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" name="douches" value="Oui" id="new-equipement-douches">
                                                        <label class="form-check-label" for="new-equipement-douches">Douches</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" name="sanitaires" value="Oui" id="new-equipement-sanitaires">
                                                        <label class="form-check-label" for="new-equipement-sanitaires">Sanitaires</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" name="arrete_ouverture" value="Oui" id="new-equipement-arrete">
                                                        <label class="form-check-label" for="new-equipement-arrete">Arrêté d'ouverture</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card mb-3">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0"><i class="bi bi-universal-access me-2"></i>Accessibilité</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label for="new-equipement-pmr" class="form-label">Accessibilité PMR</label>
                                                    <select class="form-select" name="acces_handi_mobilite" id="new-equipement-pmr">
                                                        <option value="">-- Sélectionner --</option>
                                                        <option value="Aire de jeu">Aire de jeu</option>
                                                        <option value="Vestiaires">Vestiaires</option>
                                                        <option value="Complet">Complet</option>
                                                        <option value="Aucun">Aucun</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="new-equipement-sensoriel" class="form-label">Accessibilité sensorielle</label>
                                                    <select class="form-select" name="acces_handi_sensoriel" id="new-equipement-sensoriel">
                                                        <option value="">-- Sélectionner --</option>
                                                        <option value="Aire de jeu">Aire de jeu</option>
                                                        <option value="Vestiaires">Vestiaires</option>
                                                        <option value="Complet">Complet</option>
                                                        <option value="Aucun">Aucun</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" name="acces_libre" value="Oui" id="new-equipement-acces-libre">
                                                        <label class="form-check-label" for="new-equipement-acces-libre">Accès libre</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" name="ouverture_saisonniere" value="Oui" id="new-equipement-saisonnier">
                                                        <label class="form-check-label" for="new-equipement-saisonnier">Ouverture saisonnière</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Finaliser -->
                                <div class="tab-pane fade" id="finaliser" role="tabpanel">
                                    <div class="card mb-3">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0"><i class="bi bi-people me-2"></i>Capacité d'accueil</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label for="new-equipement-capacite-actuelle" class="form-label">Capacité actuelle</label>
                                                    <input type="number" class="form-control" name="capacite_actuelle" id="new-equipement-capacite-actuelle" min="500" max="5000">
                                                    <small class="form-text text-muted">Entre 500 et 5000 personnes</small>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="new-equipement-capacite-max" class="form-label">Capacité maximale</label>
                                                    <input type="number" class="form-control" name="capacite_maximale" id="new-equipement-capacite-max" min="500" max="5000">
                                                    <small class="form-text text-muted">Entre 500 et 5000 personnes</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card mb-3">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0"><i class="bi bi-info-square me-2"></i>Informations complémentaires</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-12">
                                                    <label for="new-equipement-activites" class="form-label">Activités</label>
                                                    <textarea class="form-control" name="activites" id="new-equipement-activites" rows="2" placeholder="Ex: Tennis, Basketball, Football"></textarea>
                                                </div>
                                                <div class="col-md-12">
                                                    <label for="new-equipement-website" class="form-label">Site web</label>
                                                    <input type="url" class="form-control" name="website" id="new-equipement-website" placeholder="https://exemple.com">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary" id="prevBtn" style="display: none;">
                                <i class="bi bi-arrow-left me-1"></i> Précédent
                            </button>
                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Annuler</button>
                            <div>
                                <button type="button" class="btn btn-warning" id="nextBtn">
                                    Suivant <i class="bi bi-arrow-right ms-1"></i>
                                </button>
                                <button type="submit" name="add" value="1" class="btn btn-success" id="submitBtn" style="display: none;">
                                    <i class="bi bi-check-circle me-1"></i> Créer l'équipement
                                </button>
                            </div>
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

    </main>

</body>
</html>