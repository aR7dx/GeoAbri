<!DOCTYPE html>
<html lang="fr">

<?php require_once dirname(dirname(__DIR__)) . '/Views/Includes/meta.php'; ?>

<link rel="stylesheet" href="<?= asset('css/dashboard.css') ?>/>

<body>

    <?php require_once __DIR__ . './../Components/dashboard-header.php'; ?>
    <?php require_once __DIR__ . './../Components/dashboard-sidebar.php'; ?>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Modifier l'équipement : <?= htmlspecialchars($equipement['nom'] ?? 'Sans nom') ?></h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="<?= $router->generate('admin_equipements') ?>" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="<?= $router->generate('update_equipement') ?>">
                    <input type="hidden" name="installation_numero" value="<?= htmlspecialchars($equipement['installation_numero']) ?>">
                    <input type="hidden" name="edit" value="1">

                    <!-- Informations générales -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="border-bottom pb-2 mb-3">Informations générales</h5>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="nom" class="form-label">Nom de l'équipement <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nom" name="nom" value="<?= htmlspecialchars($equipement['nom'] ?? '') ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="type" class="form-label">Type</label>
                            <input type="text" class="form-control" id="type" name="type" value="<?= htmlspecialchars($equipement['type'] ?? '') ?>">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($equipement['description'] ?? '') ?></textarea>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="commune" class="form-label">Commune <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="commune" name="commune" value="<?= htmlspecialchars($equipement['commune'] ?? '') ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="type_famille" class="form-label">Famille d'équipement</label>
                            <input type="text" class="form-control" id="type_famille" name="type_famille" value="<?= htmlspecialchars($equipement['type_famille'] ?? '') ?>">
                        </div>
                    </div>

                    <!-- Coordonnées et localisation -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="border-bottom pb-2 mb-3">Localisation</h5>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="coordonnees_y" class="form-label">Latitude</label>
                            <input type="number" step="0.000001" class="form-control" id="coordonnees_y" name="coordonnees_y" value="<?= htmlspecialchars($equipement['coordonnees_y'] ?? '') ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="coordonnees_x" class="form-label">Longitude</label>
                            <input type="number" step="0.000001" class="form-control" id="coordonnees_x" name="coordonnees_x" value="<?= htmlspecialchars($equipement['coordonnees_x'] ?? '') ?>">
                        </div>
                    </div>

                    <!-- Propriétaire et gestion -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="border-bottom pb-2 mb-3">Propriétaire et gestion</h5>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="proprietaire_principal_nom" class="form-label">Propriétaire principal</label>
                            <input type="text" class="form-control" id="proprietaire_principal_nom" name="proprietaire_principal_nom" value="<?= htmlspecialchars($equipement['proprietaire_principal_nom'] ?? '') ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="proprietaire_principal_type" class="form-label">Type de propriétaire</label>
                            <select class="form-select" id="proprietaire_principal_type" name="proprietaire_principal_type">
                                <option value="">-- Sélectionner --</option>
                                <option value="Commune" <?= ($equipement['proprietaire_principal_type'] ?? '') == 'Commune' ? 'selected' : '' ?>>Commune</option>
                                <option value="Département" <?= ($equipement['proprietaire_principal_type'] ?? '') == 'Département' ? 'selected' : '' ?>>Département</option>
                                <option value="Région" <?= ($equipement['proprietaire_principal_type'] ?? '') == 'Région' ? 'selected' : '' ?>>Région</option>
                                <option value="État" <?= ($equipement['proprietaire_principal_type'] ?? '') == 'État' ? 'selected' : '' ?>>État</option>
                                <option value="Privé" <?= ($equipement['proprietaire_principal_type'] ?? '') == 'Privé' ? 'selected' : '' ?>>Privé</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="gestionnaire_type" class="form-label">Type de gestionnaire</label>
                            <select class="form-select" id="gestionnaire_type" name="gestionnaire_type">
                                <option value="">-- Sélectionner --</option>
                                <option value="Commune" <?= ($equipement['gestionnaire_type'] ?? '') == 'Commune' ? 'selected' : '' ?>>Commune</option>
                                <option value="Établissement Public" <?= ($equipement['gestionnaire_type'] ?? '') == 'Établissement Public' ? 'selected' : '' ?>>Établissement Public</option>
                                <option value="Association" <?= ($equipement['gestionnaire_type'] ?? '') == 'Association' ? 'selected' : '' ?>>Association</option>
                                <option value="Entreprise Privée" <?= ($equipement['gestionnaire_type'] ?? '') == 'Entreprise Privée' ? 'selected' : '' ?>>Entreprise Privée</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="gestion_dsp" class="form-label">Gestion DSP</label>
                            <select class="form-select" id="gestion_dsp" name="gestion_dsp">
                                <option value="">-- Sélectionner --</option>
                                <option value="Oui" <?= ($equipement['gestion_dsp'] ?? '') == 'Oui' ? 'selected' : '' ?>>Oui</option>
                                <option value="Non" <?= ($equipement['gestion_dsp'] ?? '') == 'Non' ? 'selected' : '' ?>>Non</option>
                            </select>
                        </div>
                    </div>

                    <!-- Caractéristiques techniques -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="border-bottom pb-2 mb-3">Caractéristiques techniques</h5>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="nature" class="form-label">Nature</label>
                            <select class="form-select" id="nature" name="nature">
                                <option value="">-- Sélectionner --</option>
                                <option value="Découvert" <?= ($equipement['nature'] ?? '') == 'Découvert' ? 'selected' : '' ?>>Découvert</option>
                                <option value="Couvert" <?= ($equipement['nature'] ?? '') == 'Couvert' ? 'selected' : '' ?>>Couvert</option>
                                <option value="Semi-couvert" <?= ($equipement['nature'] ?? '') == 'Semi-couvert' ? 'selected' : '' ?>>Semi-couvert</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="aire_nature_sol" class="form-label">Nature du sol</label>
                            <input type="text" class="form-control" id="aire_nature_sol" name="aire_nature_sol" value="<?= htmlspecialchars($equipement['aire_nature_sol'] ?? '') ?>">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="chauffage_energie" class="form-label">Énergie de chauffage</label>
                            <select class="form-select" id="chauffage_energie" name="chauffage_energie">
                                <option value="">-- Sélectionner --</option>
                                <option value="Électricité" <?= ($equipement['chauffage_energie'] ?? '') == 'Électricité' ? 'selected' : '' ?>>Électricité</option>
                                <option value="Gaz" <?= ($equipement['chauffage_energie'] ?? '') == 'Gaz' ? 'selected' : '' ?>>Gaz</option>
                                <option value="Fioul" <?= ($equipement['chauffage_energie'] ?? '') == 'Fioul' ? 'selected' : '' ?>>Fioul</option>
                                <option value="Bois" <?= ($equipement['chauffage_energie'] ?? '') == 'Bois' ? 'selected' : '' ?>>Bois</option>
                                <option value="Aucun" <?= ($equipement['chauffage_energie'] ?? '') == 'Aucun' ? 'selected' : '' ?>>Aucun</option>
                            </select>
                        </div>
                    </div>

                    <!-- Dimensions -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="border-bottom pb-2 mb-3">Dimensions de l'aire</h5>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="aire_longueur" class="form-label">Longueur (m)</label>
                            <input type="number" step="0.1" class="form-control" id="aire_longueur" name="aire_longueur" value="<?= htmlspecialchars($equipement['aire_longueur'] ?? '') ?>">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="aire_largeur" class="form-label">Largeur (m)</label>
                            <input type="number" step="0.1" class="form-control" id="aire_largeur" name="aire_largeur" value="<?= htmlspecialchars($equipement['aire_largeur'] ?? '') ?>">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="aire_hauteur" class="form-label">Hauteur (m)</label>
                            <input type="number" step="0.1" class="form-control" id="aire_hauteur" name="aire_hauteur" value="<?= htmlspecialchars($equipement['aire_hauteur'] ?? '') ?>">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="aire_surface" class="form-label">Surface (m²)</label>
                            <input type="number" step="0.1" class="form-control" id="aire_surface" name="aire_surface" value="<?= htmlspecialchars($equipement['aire_surface'] ?? '') ?>">
                        </div>
                    </div>

                    <!-- Équipements et commodités -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="border-bottom pb-2 mb-3">Équipements et commodités</h5>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="vestiaires_sportifs_nb" class="form-label">Nombre de vestiaires sportifs</label>
                            <input type="number" step="0.1" class="form-control" id="vestiaires_sportifs_nb" name="vestiaires_sportifs_nb" value="<?= htmlspecialchars($equipement['vestiaires_sportifs_nb'] ?? '') ?>">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="vestiaires_arbitres_nb" class="form-label">Nombre de vestiaires arbitres</label>
                            <input type="number" step="0.1" class="form-control" id="vestiaires_arbitres_nb" name="vestiaires_arbitres_nb" value="<?= htmlspecialchars($equipement['vestiaires_arbitres_nb'] ?? '') ?>">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="places_tibune_nb" class="form-label">Places en tribune</label>
                            <input type="number" step="0.1" class="form-control" id="places_tibune_nb" name="places_tibune_nb" value="<?= htmlspecialchars($equipement['places_tibune_nb'] ?? '') ?>">
                        </div>

                        <div class="col-md-3 mb-3">
                            <div class="form-check form-switch mt-4">
                                <input class="form-check-input" type="checkbox" id="aire_eclairage" name="aire_eclairage" value="Oui" <?= ($equipement['aire_eclairage'] ?? '') == 'Oui' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="aire_eclairage">Éclairage</label>
                            </div>
                        </div>

                        <div class="col-md-3 mb-3">
                            <div class="form-check form-switch mt-4">
                                <input class="form-check-input" type="checkbox" id="douches" name="douches" value="Oui" <?= ($equipement['douches'] ?? '') == 'Oui' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="douches">Douches</label>
                            </div>
                        </div>

                        <div class="col-md-3 mb-3">
                            <div class="form-check form-switch mt-4">
                                <input class="form-check-input" type="checkbox" id="sanitaires" name="sanitaires" value="Oui" <?= ($equipement['sanitaires'] ?? '') == 'Oui' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="sanitaires">Sanitaires</label>
                            </div>
                        </div>

                        <div class="col-md-3 mb-3">
                            <div class="form-check form-switch mt-4">
                                <input class="form-check-input" type="checkbox" id="arrete_ouverture" name="arrete_ouverture" value="Oui" <?= ($equipement['arrete_ouverture'] ?? '') == 'Oui' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="arrete_ouverture">Arrêté d'ouverture</label>
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="autres_locaux" class="form-label">Autres locaux</label>
                            <textarea class="form-control" id="autres_locaux" name="autres_locaux" rows="2"><?= htmlspecialchars($equipement['autres_locaux'] ?? '') ?></textarea>
                            <small class="form-text text-muted">Ex: Réception/Accueil, Bureau(x), Buvette, Local de rangement, etc.</small>
                        </div>
                    </div>

                    <!-- Accessibilité -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="border-bottom pb-2 mb-3">Accessibilité</h5>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="acces_handi_mobilite" class="form-label">Accessibilité handicapés à mobilité réduite</label>
                            <select class="form-select" id="acces_handi_mobilite" name="acces_handi_mobilite">
                                <option value="">-- Sélectionner --</option>
                                <option value="Aire de jeu" <?= ($equipement['acces_handi_mobilite'] ?? '') == 'Aire de jeu' ? 'selected' : '' ?>>Aire de jeu</option>
                                <option value="Vestiaires" <?= ($equipement['acces_handi_mobilite'] ?? '') == 'Vestiaires' ? 'selected' : '' ?>>Vestiaires</option>
                                <option value="Complet" <?= ($equipement['acces_handi_mobilite'] ?? '') == 'Complet' ? 'selected' : '' ?>>Complet</option>
                                <option value="Aucun" <?= ($equipement['acces_handi_mobilite'] ?? '') == 'Aucun' ? 'selected' : '' ?>>Aucun</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="acces_handi_sensoriel" class="form-label">Accessibilité handicapés sensoriels</label>
                            <select class="form-select" id="acces_handi_sensoriel" name="acces_handi_sensoriel">
                                <option value="">-- Sélectionner --</option>
                                <option value="Aire de jeu" <?= ($equipement['acces_handi_sensoriel'] ?? '') == 'Aire de jeu' ? 'selected' : '' ?>>Aire de jeu</option>
                                <option value="Vestiaires" <?= ($equipement['acces_handi_sensoriel'] ?? '') == 'Vestiaires' ? 'selected' : '' ?>>Vestiaires</option>
                                <option value="Complet" <?= ($equipement['acces_handi_sensoriel'] ?? '') == 'Complet' ? 'selected' : '' ?>>Complet</option>
                                <option value="Aucun" <?= ($equipement['acces_handi_sensoriel'] ?? '') == 'Aucun' ? 'selected' : '' ?>>Aucun</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="acces_libre" name="acces_libre" value="Oui" <?= ($equipement['acces_libre'] ?? '') == 'Oui' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="acces_libre">Accès libre</label>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="ouverture_saisonniere" name="ouverture_saisonniere" value="Oui" <?= ($equipement['ouverture_saisonniere'] ?? '') == 'Oui' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="ouverture_saisonniere">Ouverture saisonnière</label>
                            </div>
                        </div>
                    </div>

                    <!-- Capacité -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="border-bottom pb-2 mb-3">Capacité</h5>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="capacite_actuelle" class="form-label">Capacité actuelle</label>
                            <input type="number" class="form-control" id="capacite_actuelle" name="capacite_actuelle" value="<?= htmlspecialchars($equipement['capacite_actuelle'] ?? '') ?>" min="500" max="5000">
                            <small class="form-text text-muted">Entre 500 et 5000 personnes</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="capacite_maximale" class="form-label">Capacité maximale</label>
                            <input type="number" class="form-control" id="capacite_maximale" name="capacite_maximale" value="<?= htmlspecialchars($equipement['capacite_maximale'] ?? '') ?>" min="500" max="5000">
                            <small class="form-text text-muted">Entre 500 et 5000 personnes</small>
                        </div>
                    </div>

                    <!-- ERP -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="border-bottom pb-2 mb-3">ERP (Établissement Recevant du Public)</h5>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="erp_type" class="form-label">Type ERP</label>
                            <input type="text" class="form-control" id="erp_type" name="erp_type" value="<?= htmlspecialchars($equipement['erp_type'] ?? '') ?>">
                            <small class="form-text text-muted">Ex: RPE, CTS, X, R</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="erp_cat" class="form-label">Catégorie ERP</label>
                            <select class="form-select" id="erp_cat" name="erp_cat">
                                <option value="">-- Sélectionner --</option>
                                <option value="1" <?= ($equipement['erp_cat'] ?? '') == '1' ? 'selected' : '' ?>>1 (> 1500 personnes)</option>
                                <option value="2" <?= ($equipement['erp_cat'] ?? '') == '2' ? 'selected' : '' ?>>2 (701 à 1500 personnes)</option>
                                <option value="3" <?= ($equipement['erp_cat'] ?? '') == '3' ? 'selected' : '' ?>>3 (301 à 700 personnes)</option>
                                <option value="4" <?= ($equipement['erp_cat'] ?? '') == '4' ? 'selected' : '' ?>>4 (< 300 personnes)</option>
                                <option value="5" <?= ($equipement['erp_cat'] ?? '') == '5' ? 'selected' : '' ?>>5 (selon seuil d'assujettissement)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Activités et observations -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="border-bottom pb-2 mb-3">Activités et informations complémentaires</h5>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="activites" class="form-label">Activités</label>
                            <textarea class="form-control" id="activites" name="activites" rows="2"><?= htmlspecialchars($equipement['activites'] ?? '') ?></textarea>
                            <small class="form-text text-muted">Ex: Tennis, Basketball, Football, etc.</small>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="utilisateurs" class="form-label">Utilisateurs</label>
                            <input type="text" class="form-control" id="utilisateurs" name="utilisateurs" value="<?= htmlspecialchars($equipement['utilisateurs'] ?? '') ?>">
                            <small class="form-text text-muted">Ex: Clubs sportifs, comités, ligues, fédérations</small>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="website" class="form-label">Site web</label>
                            <input type="url" class="form-control" id="website" name="website" value="<?= htmlspecialchars($equipement['website'] ?? '') ?>">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="observations" class="form-label">Observations</label>
                            <textarea class="form-control" id="observations" name="observations" rows="3"><?= htmlspecialchars($equipement['observations'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2">
                                <button type="submit" class="btn btn-success">Enregistrer les modifications</button>
                                <a href="<?= $router->generate('admin_equipements') ?>" class="btn btn-outline-danger">Annuler</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </main>

</body>
</html>
