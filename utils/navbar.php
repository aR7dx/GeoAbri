<?php
include_once 'router.php';
?>

<nav class="navbar navbar-expand-lg mb-4 bg-light"> 
    <div class="container-fluid"> 
        <a class="navbar-brand" href="https://www.normandie.fr/">
            <img src="<?= Router::$logo_navbar ?>" alt="Logo" width="50" height="50" class="d-inline-block">
            <span style="color: #000; font-family: Arial, Arial Medium, sans-serif; font-weight: 500;">Équipements d'urgences</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation"> 
            <span class="navbar-toggler-icon"></span> 
        </button> 
        <div class="collapse navbar-collapse" id="navbarCollapse"> 
            <ul class="navbar-nav ms-auto"> 
                <li class="nav-item">
                    <a class="nav-link <?= (str_ends_with($_SERVER['SCRIPT_NAME'], Router::$accueil)) ? 'active' : '' ?>" href="<?= Router::$accueil ?>">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (str_ends_with($_SERVER['SCRIPT_NAME'], Router::$carte_interactive."index.php")) ? 'active' : '' ?>" href="<?= Router::$carte_interactive ?>">Carte interactive</a>
                </li>

                <?php if (isset($_SESSION['user'])) : ?>
                    <li class="nav-item">
                        <a class="nav-link <?= (str_ends_with($_SERVER['SCRIPT_NAME'], Router::$compte)) ? 'active' : '' ?>" href="<?= Router::$compte ?>">Mon compte</a>
                    </li>
                <?php else : ?>
                    <li class="nav-item">
                        <a class="nav-link <?= (str_ends_with($_SERVER['SCRIPT_NAME'], Router::$connexion)) ? 'active' : '' ?>" href="<?= Router::$connexion ?>">Connexion</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (str_ends_with($_SERVER['SCRIPT_NAME'], Router::$connexion)) ? 'active' : '' ?>" href="<?= Router::$inscription ?>">Inscription</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div> 
    </div>
</nav>