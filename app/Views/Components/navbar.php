<nav id="navbar" class="navbar navbar-expand-lg bg-light border-bottom">
    <div class="container-fluid">
        <a class="navbar-brand user-select-none" href="<?= $router->generate('home'); ?>">
            <img src="/public/media/logo_normandie.jpg" alt="Logo" width="50" height="50" class="d-inline-block">
            <span>GeoAbri</span>
        </a>

        <!-- Bouton de burger menu, ouvre l'Offcanvas -->
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Offcanvas Menu -->
        <div class="offcanvas offcanvas-start"  id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
                <h5 id="offcanvasNavbarLabel" class="offcanvas-title">Menu</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="user-select-none nav-link rounded <?= (str_ends_with($_SERVER['REDIRECT_URL'], '/')) ? 'btn-nav text-light px-2' : '' ?>" href="<?= $router->generate('home'); ?>">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="user-select-none nav-link rounded <?= (str_ends_with($_SERVER['REDIRECT_URL'], '/map')) ? 'btn-nav text-light px-2' : '' ?>" href="<?= $router->generate('map'); ?>">Carte</a>
                    </li>

                    <?php if (isset($_SESSION['user']['permissions']) && (in_array("create_alert", $_SESSION['user']['permissions']) || in_array("view_all_alerts", $_SESSION['user']['permissions']))): ?>
                        <li class="nav-item">
                            <a class="user-select-none nav-link rounded <?= (str_contains($_SERVER['REDIRECT_URL'], '/alert')) ? 'btn-nav text-light px-2' : '' ?>" href="<?= isset($_SESSION['user']['permissions']) && in_array("view_all_alerts", $_SESSION['user']['permissions']) ? $router->generate('admin_alerts') : $router->generate('create_alert'); ?>">
                                Alertes
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['user']['permissions']) && in_array("access_dashboard", $_SESSION['user']['permissions'])): ?>
                        <li class="nav-item">
                            <a class="user-select-none nav-link rounded <?= (str_contains($_SERVER['REDIRECT_URL'], '/dashboard')) ? 'btn-nav text-light px-2' : '' ?>" href="<?= (isset($_SESSION['user']) && in_array('view_all_stats', $_SESSION['user']['permissions'])) ? $router->generate('dashboard') : $router->generate('admin_equipements'); ?>">Dashboard</a>
                        </li>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['user']['connected']) && $_SESSION['user']['connected'] == 1) : ?>
                        <li class="nav-item">
                            <div class="dropdown">
                                <a id="dropdownAccount" class="user-select-none nav-link dropdown-toggle <?= str_contains($_SERVER['REDIRECT_URL'], '/account') ? 'active btn-nav rounded text-light px-2' : '' ?>" data-bs-toggle="dropdown" aria-expanded="false" style="user-select: none; cursor: pointer;">
                                    Mon compte
                                </a>
                                <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownAccount">
                                    <li><a class="dropdown-item" href="<?= $router->generate('account'); ?>">Profil</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="<?= $router->generate('logout'); ?>">Déconnexion</a></li>
                                </ul>
                            </div>
                        </li>
                    <?php else : ?>
                        <li class="nav-item">
                            <a class="user-select-none nav-link rounded <?= (str_ends_with($_SERVER['REDIRECT_URL'], '/login')) ? 'btn-nav text-light px-2' : '' ?>" href="<?= $router->generate('login'); ?>">Connexion</a>
                        </li>
                        <li class="nav-item">
                            <a class="user-select-none nav-link rounded <?= (str_ends_with($_SERVER['REDIRECT_URL'], '/register')) ? 'btn-nav text-light px-2' : '' ?>" href="<?= $router->generate('register'); ?>">Inscription</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</nav>