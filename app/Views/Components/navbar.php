<nav id="navbar" class="navbar navbar-expand-lg bg-light border-bottom"> 
    <div class="container-fluid"> 
        <a class="navbar-brand" href="<?= $router->generate('home'); ?>">
            <img src="/public/media/logo_normandie.jpg" alt="Logo" width="50" height="50" class="d-inline-block">
            <span style="color: #000; font-family: Arial, Arial Medium, sans-serif; font-weight: 500;">Équipements d'urgences</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation"> 
            <span class="navbar-toggler-icon"></span>
        </button> 
        <div class="collapse navbar-collapse" id="navbarCollapse"> 
            <ul class="navbar-nav ms-auto gap-2"> 
                <div class="d-flex">
                    <li class="nav-item">
                        <a class="nav-link <?= (str_ends_with($_SERVER['REDIRECT_URL'], '/')) ? 'active' : '' ?>" href="<?= $router->generate('home'); ?>">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (str_ends_with($_SERVER['REDIRECT_URL'], '/map')) ? 'active' : '' ?>" href="<?= $router->generate('map'); ?>">Carte</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (str_ends_with($_SERVER['REDIRECT_URL'], '/api')) ? 'active' : '' ?>" href="<?= $router->generate('api'); ?>">API</a>
                    </li>
                </div>

                <?php if (isset($_SESSION['user']['connected']) && $_SESSION['user']['connected'] == 1) : ?>
                    <li class="nav-item">
                        <div class="dropdown">
                            <a id="dropdownAccount" class="nav-link dropdown-toggle <?= (str_ends_with($_SERVER['REDIRECT_URL'], '/account')) ? 'active' : '' ?>" data-bs-toggle="dropdown" aria-expanded="false" style="user-select: none; cursor: pointer;">
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
                    <div class="d-flex">
                        <li class="nav-item">
                            <a class="nav-link bg-primary rounded text-light <?= (str_ends_with($_SERVER['REDIRECT_URL'], '/login')) ? 'active' : '' ?>" href="<?= $router->generate('logon'); ?>">Connexion</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= (str_ends_with($_SERVER['REDIRECT_URL'], '/register')) ? 'active' : '' ?>" href="<?= $router->generate('register'); ?>">Inscription</a>
                        </li>
                    </div>
                <?php endif; ?>
            </ul>
        </div> 
    </div>
</nav>