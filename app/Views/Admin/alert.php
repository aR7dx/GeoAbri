<!DOCTYPE html>
<html lang="fr">

<?php require_once dirname(dirname(__DIR__)) . '/Views/Includes/meta.php'; ?>

<link rel="stylesheet" href=<?= asset('css/dashboard.css') ?>/>
<link rel="stylesheet" href="<?= asset('css/alert.css') ?>/>

<body>

    <?php require_once __DIR__ . './../Components/dashboard-header.php'; ?>
    <?php require_once __DIR__ . './../Components/dashboard-sidebar.php'; ?>

    <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-1 mb-3">
            <div class="d-flex flex-column gap-1">
                <h1 class="h2">Gestion des alertes</h1>
                <p class="text-muted">Créez et gérez les alertes pour votre territoire</p>     
            </div>    
            <div class="btn-toolbar mb-2 mb-md-0">
                <button id="add-equipement" type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addAlertModal">Créer une alerte</button>
            </div>
        </div>

        <?php if (isset($_SESSION['alert_error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Erreur !</strong> <?= htmlspecialchars($_SESSION['alert_error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['alert_error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['alert_success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Succès !</strong> <?= htmlspecialchars($_SESSION['alert_success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['alert_success']); ?>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Erreur !</strong> <?= htmlspecialchars($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Succès !</strong> <?= htmlspecialchars($success); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- modal pour creer une alerte -->
        <form method="POST" action="<?= $router->generate('store_alert'); ?>">
            <div id="addAlertModal" class="modal fade" tabindex="-1" aria-labelledby="addAlertModal" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5 fw-bold text-dark">📢 Créer une nouvelle alerte</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body d-flex flex-column p-4 pb-0 gap-3">

                        
                            <div class="">
                                <label for="nom" class="form-label fw-semibold">Nom de l'alerte <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nom" name="nom" required maxlength="50" placeholder="Ex: Fermeture gymnase municipal, Tempêtes, Orages...">
                            </div>

                            <div class="">
                                <label for="description" class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="description" name="description" rows="4" required maxlength="300" placeholder="Décrivez la situation..."></textarea>
                                <small class="text-muted">Maximum 300 caractères</small>
                            </div>

                            <div class="">
                                <label for="niveau" class="form-label fw-semibold">Niveau d'alerte <span class="text-danger">*</span></label>
                                <select class="form-select" id="niveau" name="niveau" required>
                                    <option value="1">⚠️ Niveau 1 - Information</option>
                                    <option value="2">🔶 Niveau 2 - Attention</option>
                                    <option value="3">🚨 Niveau 3 - Urgent</option>
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <label for="date_debut" class="form-label fw-semibold">Date de début <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="date_debut" name="date_debut" required min="<?= date('Y-m-d'); ?>">
                                </div>

                                <div class="col-md-6">
                                    <label for="date_fin" class="form-label fw-semibold">Date de fin</label>
                                    <input type="date" class="form-control" id="date_fin" name="date_fin">
                                    <small class="text-muted user-select-none">Laissez vide si indéterminée</small>
                                </div>
                            </div>

                            <div class="">
                                <label for="ville" class="form-label fw-semibold">Ville <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="ville" name="ville" required maxlength="100" placeholder="Ex: Caen">
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <label for="lat" class="form-label fw-semibold">Latitude <span class="text-danger">*</span></label>
                                    <input type="number" step="0.000001" class="form-control" id="lat" name="lat" required min="-90" max="90" placeholder="49.182863">
                                </div>

                                <div class="col-md-6">
                                    <label for="lon" class="form-label fw-semibold">Longitude <span class="text-danger">*</span></label>
                                    <input type="number" step="0.000001" class="form-control" id="lon" name="lon" required min="-180" max="180" placeholder="-0.370679">
                                </div>
                            </div>

                            <small class="text-muted d-block user-select-none mb-2">💡 Astuce : Utilisez Google Maps pour obtenir les coordonnées GPS précises</small>


                        </div>
                        <div class="modal-footer d-flex flex-row border-0 w-100" style="flex-wrap: nowrap;">
                            <button type="submit" class="btn btn-success w-100">Créer l'alerte</button>
                            <button type="button" class="btn btn-outline-secondary w-100" data-bs-dismiss="modal">Annuler</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

            <!-- Liste des alertes de l'utilisateur -->
            <div class="col-md-12">
                <div class="card shadow-sm p-4">
                    <h5 class="fw-bold mb-4"><?= (isset($_SESSION['user']['permissions']) && in_array('view_all_alerts', $_SESSION['user']['permissions'])) ? '📋 Alertes actives' : '📋 Mes alertes actives'; ?></h5>

                    <?php
                    try {
                        if (isset($alerts) && empty($alerts)) {
                            echo '<p class="text-muted text-center py-4">Aucune alerte créée pour le moment</p>';
                        } else {
                            foreach ($alerts as $alert) {
                                $isActive = empty($alert['date_fin']) || strtotime($alert['date_fin']) >= time();
                                $niveauClass = 'niveau-' . $alert['niveau'];
                                $niveauText = ['', '⚠️ Information', '🔶 Attention', '🚨 Urgent'][$alert['niveau']];
                                ?>
                                <div class="alert-card card mb-3 <?= $isActive ? 'border-danger' : 'border-secondary' ?>">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="fw-bold mb-0 user-select-none"><?= htmlspecialchars($alert['nom']); ?></h6>
                                            <span class="alert-niveau-badge user-select-none <?= $niveauClass; ?>"><?= $niveauText; ?></span>
                                        </div>
                                        
                                        <p class="text-muted small mb-2 user-select-none"><?= htmlspecialchars($alert['description']); ?></p>
                                        
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="small text-muted">
                                                <div class="user-select-none">📍 <?= htmlspecialchars($alert['ville']); ?></div>
                                                <div class="user-select-none">📅 Du <?= date('d/m/Y', strtotime($alert['date_debut'])); ?>
                                                <?= $alert['date_fin'] ? ' au ' . date('d/m/Y', strtotime($alert['date_fin'])) : ''; ?>
                                                </div>
                                            </div>
                                            
                                            <a href="<?= $router->generate('delete_alert'); ?>?id=<?= $alert['id_alerte']; ?>" 
                                               class="btn btn-sm btn-outline-danger"
                                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette alerte ?');">
                                                Supprimer
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                    } catch (Exception $e) {
                        echo '<p class="text-danger text-center">Erreur lors du chargement des alertes</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

</body>
</html>