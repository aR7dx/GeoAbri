<?php 
include_once '../utils/header.php';
include_once '../utils/meta.php';
$titre = "Mon compte";
?>

<body>
    <?php include_once Router::$navbar; ?>

    <?php
        if (!isset($_SESSION['connected'])) {
            header('Location: /');
        }
    ?>

    <h3>Bienvenue sur votre compte</h3>

    <button type="button" class="btn btn-primary"
    onclick="window.location.href='/connexion/deconnexion.php'">Se déconnecter</button>
</body>