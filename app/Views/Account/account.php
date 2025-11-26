<!DOCTYPE html>
<html lang="fr">

<?php require_once dirname(dirname(__DIR__)) . '/Views/Includes/meta.php'; ?>

<body>

    <?php require_once __DIR__ . './../Components/navbar.php'; ?>

    <div class="container mt-5">
        <h3>Bienvenue sur votre compte <?= $_SESSION['user']['prenom']; ?></h3>

        <p>En développement...</p>

        <div class="d-flex flex-column">
            <span><strong>id:</strong> <?= $_SESSION['user']['id']; ?></span>
            <span><strong>prenom:</strong> <?= $_SESSION['user']['prenom']; ?></span>
            <span><strong>nom:</strong> <?= $_SESSION['user']['nom']; ?></span>
            <span><strong>role:</strong> <?= $_SESSION['user']['role']; ?></span>
            <span><strong>email:</strong> <?= $_SESSION['user']['email']; ?></span>
            <span><strong>permissions:</strong> <?= implode(',', $_SESSION['user']['permissions']); ?></span>
            <span><strong>connected:</strong> <?= $_SESSION['user']['connected']; ?></span>
        </div>
    </div>

</body>
</html>