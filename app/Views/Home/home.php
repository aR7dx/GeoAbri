<!DOCTYPE html>
<html lang="fr">
    
<?php require_once dirname(dirname(__DIR__)) . '/Views/Includes/meta.php'; ?>

<body>
<?php require_once __DIR__ . '/../Components/navbar.php'; ?>

<main>
    <section class="py-5 bg-light"> <div class="container">
            <?php 
            // Couleur rouge/danger pour simuler le style original
            $primary_red_class = 'text-danger'; 
            ?>
            <?php if (isset($_SESSION['user']['connected']) && $_SESSION['user']['connected'] == 1): ?>
                <div class="alert alert-danger border-start border-4 border-danger rounded-3 mb-4 p-3"> <h5 class="mb-0">
                        👋 Bienvenue, <strong><?= htmlspecialchars((ucfirst(strtolower($_SESSION['user']['prenom'])) . ' ' . ucfirst(strtolower($_SESSION['user']['nom']))) ?? strtolower($_SESSION['user']['email']) ?? 'Utilisateur'); ?></strong> !
                    </h5>
                </div>
            <?php endif; ?>
            
            <div class="text-center">
                <h1 class="display-4 fw-bold mb-4">Trouvez votre équipement sportif</h1>
                <p class="lead text-muted mb-4">
                    Accédez à la base de données complète des équipements sportifs publics en France.<br>
                    Recherchez par localisation, type d'activité ou caractéristiques spécifiques.
                </p>
                
                <div class="mx-auto" style="max-width: 600px;"> <form action="<?= $router->generate('map'); ?>" method="GET" class="input-group input-group-lg shadow-sm">
                        <input type="text" class="form-control" name="q" placeholder="Ville, code postal ou type d'équipement..." aria-label="Recherche">
                        <button class="btn btn-danger" type="submit"> 🔍 Rechercher
                        </button>
                    </form>
                    <small class="text-muted d-block mt-2">
                        Exemple : "Caen", "Gymnase", "Terrain de basket"...
                    </small>
                </div>
                
                <?php if (!isset($_SESSION['user']['connected']) || $_SESSION['user']['connected'] != 1): ?>
                    <div class="mt-4">
                        <a href="<?= $router->generate('register'); ?>" class="btn btn-outline-danger btn-lg">
                            Créer un compte gratuitement
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <h2 class="text-center fw-bold mb-5 position-relative">
                Explorez par catégorie
                <div class="mx-auto border-bottom border-danger border-3 mt-2" style="width: 60px;"></div>
            </h2>
            
            <div class="row g-4">
                <div class="col-md-3 col-sm-6">
                    <a href="<?= $router->generate('map'); ?>?category=terrain" class="text-decoration-none text-dark">
                        <div class="card p-4 text-center border h-100 shadow-sm"> <div class="fs-1 text-danger mb-3">⚽</div> <h5 class="card-title">Terrains de sport</h5>
                            <p class="text-muted small">Football, rugby, athlétisme...</p>
                        </div>
                    </a>
                </div>
                
                <div class="col-md-3 col-sm-6">
                    <a href="<?= $router->generate('map'); ?>?category=salle" class="text-decoration-none text-dark">
                        <div class="card p-4 text-center border h-100 shadow-sm">
                            <div class="fs-1 text-danger mb-3">🏀</div>
                            <h5 class="card-title">Salles & Gymnases</h5>
                            <p class="text-muted small">Basket, volley, handball...</p>
                        </div>
                    </a>
                </div>
                
                <div class="col-md-3 col-sm-6">
                    <a href="<?= $router->generate('map'); ?>?category=aquatique" class="text-decoration-none text-dark">
                        <div class="card p-4 text-center border h-100 shadow-sm">
                            <div class="fs-1 text-danger mb-3">🏊</div>
                            <h5 class="card-title">Équipements aquatiques</h5>
                            <p class="text-muted small">Piscines, bassins, plages...</p>
                        </div>
                    </a>
                </div>
                
                <div class="col-md-3 col-sm-6">
                    <a href="<?= $router->generate('map'); ?>?category=specialise" class="text-decoration-none text-dark">
                        <div class="card p-4 text-center border h-100 shadow-sm">
                            <div class="fs-1 text-danger mb-3">🎾</div>
                            <h5 class="card-title">Équipements spécialisés</h5>
                            <p class="text-muted small">Tennis, skate, escalade...</p>
                        </div>
                    </a>
                </div>
            </div>
            
            <div class="text-center mt-5">
                <a href="<?= $router->generate('map'); ?>" class="btn btn-danger btn-lg">
                    Voir la carte interactive 🗺️
                </a>
            </div>
        </div>
    </section>


    <section class="py-5">
        <div class="container">
            <h2 class="text-center fw-bold mb-5 position-relative">
                Statistiques
                <div class="mx-auto border-bottom border-danger border-3 mt-2" style="width: 60px;"></div>
            </h2>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card text-center p-4 border-0 shadow-sm bg-light h-100"> <div class="card-body">
                            <h2 class="display-4 fw-bold text-danger mb-2">320K+</h2>
                            <p class="text-muted mb-0">Équipements sportifs</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center p-4 border-0 shadow-sm bg-light h-100">
                        <div class="card-body">
                            <h2 class="display-4 fw-bold text-danger mb-2">35K+</h2>
                            <p class="text-muted mb-0">Communes couvertes</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center p-4 border-0 shadow-sm bg-light h-100">
                        <div class="card-body">
                            <h2 class="display-4 fw-bold text-danger mb-2">150+</h2>
                            <p class="text-muted mb-0">Types d'équipements</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center fw-bold mb-5 position-relative">
                Comment ça marche ?
                <div class="mx-auto border-bottom border-danger border-3 mt-2" style="width: 60px;"></div>
            </h2>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="text-center">
                        <div class="d-inline-flex align-items-center justify-content-center bg-danger text-white rounded-circle mb-3" style="width: 50px; height: 50px; font-size: 1.5rem;">1</div>
                        <h5 class="fw-bold mb-3">Recherchez</h5>
                        <p class="text-muted">
                            Utilisez la barre de recherche ou la carte interactive pour trouver des équipements près de chez vous.
                        </p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="text-center">
                        <div class="d-inline-flex align-items-center justify-content-center bg-danger text-white rounded-circle mb-3" style="width: 50px; height: 50px; font-size: 1.5rem;">2</div>
                        <h5 class="fw-bold mb-3">Filtrez</h5>
                        <p class="text-muted">
                            Affinez votre recherche selon le type d'équipement, l'accessibilité, les dimensions et bien plus.
                        </p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="text-center">
                        <div class="d-inline-flex align-items-center justify-content-center bg-danger text-white rounded-circle mb-3" style="width: 50px; height: 50px; font-size: 1.5rem;">3</div>
                        <h5 class="fw-bold mb-3">Consultez les détails</h5>
                        <p class="text-muted">
                            Accédez aux informations complètes : localisation, caractéristiques, accessibilité, propriétaire...
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <h2 class="text-center fw-bold mb-5 position-relative">
                GeoAbri pour tous
                <div class="mx-auto border-bottom border-danger border-3 mt-2" style="width: 60px;"></div>
            </h2>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-center bg-danger-subtle text-danger rounded-circle mx-auto mb-3" style="width: 60px; height: 60px;">
                                <span style="font-size: 2rem;">🏛️</span>
                            </div>
                            <h5 class="card-title fw-bold text-center mb-3">Collectivités</h5>
                            <p class="text-muted">
                                Gérez et mettez à jour les équipements de votre territoire. Partagez les données avec les citoyens et autres administrations.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-center bg-danger-subtle text-danger rounded-circle mx-auto mb-3" style="width: 60px; height: 60px;">
                                <span style="font-size: 2rem;">🤝</span>
                            </div>
                            <h5 class="card-title fw-bold text-center mb-3">Associations & Clubs</h5>
                            <p class="text-muted">
                                Trouvez des infrastructures adaptées pour vos entraînements et compétitions. Planifiez vos activités facilement.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-center bg-danger-subtle text-danger rounded-circle mx-auto mb-3" style="width: 60px; height: 60px;">
                                <span style="font-size: 2rem;">👥</span>
                            </div>
                            <h5 class="card-title fw-bold text-center mb-3">Particuliers</h5>
                            <p class="text-muted">
                                Découvrez les équipements sportifs publics à proximité. Pratiquez votre sport favori en toute simplicité.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <img src="public/media/map_screenshot.png" alt="Aperçu de la carte interactive" class="img-fluid rounded shadow mb-3">
                </div>
                <div class="col-md-6">
                    <h3 class="fw-bold mb-4">Une carte interactive et intuitive</h3>
                    <p class="text-muted mb-3">
                        Visualisez tous les équipements sportifs sur une carte interactive. Zoomez, filtrez et obtenez toutes les informations en un clic.
                    </p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><span class="text-success me-2">✅</span> Géolocalisation précise</li>
                        <li class="mb-2"><span class="text-success me-2">✅</span> Filtres avancés</li>
                        <li class="mb-2"><span class="text-success me-2">✅</span> Informations complètes</li>
                        <li class="mb-2"><span class="text-success me-2">✅</span> Accessibilité détaillée</li>
                    </ul>
                    <a href="<?= $router->generate('map'); ?>" class="btn btn-danger mt-3">
                        Découvrir la carte
                    </a>
                </div>
            </div>
        </div>
    </section>

    <?php if (!isset($_SESSION['user']['connected']) || $_SESSION['user']['connected'] != 1): ?>
    <section class="py-5 text-center bg-danger-subtle bg-gradient">
        <div class="container">
            <h2 class="fw-bold mb-3">Prêt à commencer ?</h2>
            <p class="lead text-muted mb-4">
                Créez votre compte gratuitement et accédez à toutes les fonctionnalités de GeoAbri.
            </p>
            <div>
                <a href="<?= $router->generate('register'); ?>" class="btn btn-danger btn-lg me-3">
                    S'inscrire gratuitement
                </a>
                <a href="<?= $router->generate('map'); ?>" class="btn btn-outline-danger btn-lg">
                    Explorer sans compte
                </a>
            </div>
        </div>
    </section>
    <?php endif; ?>
</main>

<?php require_once __DIR__ . '/../Components/footer.php'; ?>
</body>
</html>