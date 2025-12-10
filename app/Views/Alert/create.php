<!DOCTYPE html>
<html lang="fr">

<?php require_once dirname(dirname(__DIR__)) . '/Views/Includes/meta.php'; ?>

<head>
    <style>
        .alert-niveau-badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.875rem;
        }
        .niveau-1 { background-color: #ffc107; color: #000; }
        .niveau-2 { background-color: #ff9800; color: #fff; }
        .niveau-3 { background-color: #f44336; color: #fff; }
        
        .alert-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .alert-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
    </style>
</head>

<body>

    <?php require_once __DIR__ . '/../Components/navbar.php'; ?>

    <div class="container pt-4 pb-5">
        
        <div class="mb-4">
            <h2 class="fw-bold">Gestion des alertes</h2>
            <p class="text-muted">Créez et gérez les alertes pour votre territoire</p>
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

        <div class="row">
            <!-- Formulaire de création -->
            <div class="col-md-6">
                <div class="card shadow-sm p-4">
                    <h5 class="fw-bold mb-4">📢 Créer une nouvelle alerte</h5>

                    <form method="POST" action="<?= $router->generate('store_alert'); ?>">
                        
                        <div class="mb-3">
                            <label for="nom" class="form-label fw-semibold">Nom de l'alerte <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nom" name="nom" required maxlength="50" placeholder="Ex: Fermeture gymnase municipal">
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="description" name="description" rows="4" required maxlength="300" placeholder="Décrivez la situation..."></textarea>
                            <small class="text-muted">Maximum 300 caractères</small>
                        </div>

                        <div class="mb-3">
                            <label for="niveau" class="form-label fw-semibold">Niveau d'alerte <span class="text-danger">*</span></label>
                            <select class="form-select" id="niveau" name="niveau" required>
                                <option value="1">⚠️ Niveau 1 - Information</option>
                                <option value="2">🔶 Niveau 2 - Attention</option>
                                <option value="3">🚨 Niveau 3 - Urgent</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="date_debut" class="form-label fw-semibold">Date de début <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="date_debut" name="date_debut" required min="<?= date('Y-m-d'); ?>">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="date_fin" class="form-label fw-semibold">Date de fin</label>
                                <input type="date" class="form-control" id="date_fin" name="date_fin">
                                <small class="text-muted">Laissez vide si indéterminée</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="ville" class="form-label fw-semibold">Ville <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ville" name="ville" required maxlength="100" placeholder="Ex: Caen">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="lat" class="form-label fw-semibold">Latitude <span class="text-danger">*</span></label>
                                <input type="number" step="0.000001" class="form-control" id="lat" name="lat" required min="-90" max="90" placeholder="49.182863">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="lon" class="form-label fw-semibold">Longitude <span class="text-danger">*</span></label>
                                <input type="number" step="0.000001" class="form-control" id="lon" name="lon" required min="-180" max="180" placeholder="-0.370679">
                            </div>
                        </div>

                        <small class="text-muted d-block mb-3">💡 Astuce : Utilisez Google Maps pour obtenir les coordonnées GPS précises</small>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-danger">Créer l'alerte</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Liste des alertes de l'utilisateur -->
            <div class="col-md-6">
                <div class="card shadow-sm p-4">
                    <h5 class="fw-bold mb-4">📋 Mes alertes actives</h5>

                    <?php
                    try {
                        $alertModel = new App\Models\Alert\Alert();
                        $myAlerts = $alertModel->getAlertsByUser($_SESSION['user']['user_id']);
                        
                        if (empty($myAlerts)) {
                            echo '<p class="text-muted text-center py-4">Aucune alerte créée pour le moment</p>';
                        } else {
                            foreach ($myAlerts as $alert) {
                                $isActive = empty($alert['date_fin']) || strtotime($alert['date_fin']) >= time();
                                $niveauClass = 'niveau-' . $alert['niveau'];
                                $niveauText = ['', '⚠️ Information', '🔶 Attention', '🚨 Urgent'][$alert['niveau']];
                                ?>
                                <div class="alert-card card mb-3 <?= $isActive ? 'border-danger' : 'border-secondary' ?>">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="fw-bold mb-0"><?= htmlspecialchars($alert['nom']); ?></h6>
                                            <span class="alert-niveau-badge <?= $niveauClass; ?>"><?= $niveauText; ?></span>
                                        </div>
                                        
                                        <p class="text-muted small mb-2"><?= htmlspecialchars($alert['description']); ?></p>
                                        
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="small text-muted">
                                                <div>📍 <?= htmlspecialchars($alert['ville']); ?></div>
                                                <div>📅 Du <?= date('d/m/Y', strtotime($alert['date_debut'])); ?>
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

    <?php require_once __DIR__ . '/../Components/footer.php'; ?>

</body>
</html>