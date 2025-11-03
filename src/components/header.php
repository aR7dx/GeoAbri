<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="16x16" href="https://www.normandie.fr/profiles/createur_profil/themes/createur/favicon/favicon-16x16.png">
    <link rel="icon" type="image/png" sizes="32x32" href="https://www.normandie.fr/profiles/createur_profil/themes/createur/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" href="https://www.normandie.fr/profiles/createur_profil/themes/createur/favicon/favicon.ico">
    
    <title>
        <?php if (isset($title)) : ?>
            <?= $title; ?>
        <?php else : ?>
            <?= "PHP Site"; ?>
        <?php endif ?>
    </title>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg" style="background-color: rgb(210,10,40);">
        <div class="container-fluid">
            <a class="navbar-brand align-text-center" href="https://www.normandie.fr/">
                <img src="./public/img/logo_normandie.jpg" alt="Logo" width="50" height="50" class="d-inline-block">
                <span style="color: rgba(0,0,0,1); font-family: Arial, Arial Medium, sans-serif; font-weight: 500;">Équipements d'urgences</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">

                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#" style="color: rgba(0,0,0,0.7); font-family: Arial, Arial Light, sans-serif; font-weight: 300;">Accueil</a>
                    </li>

                    <?php if (isset($_SESSION['user'])) : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="#" style="color: rgba(0,0,0,0.7); font-family: Arial, Arial Light, sans-serif; font-weight: 300;">Mon compte</a>
                        </li>
                    <?php else : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="#" style="color: rgba(0,0,0,0.7); font-family: Arial, Arial Light, sans-serif; font-weight: 300;">Se Connecter</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" style="color: rgba(0,0,0,0.7); font-family: Arial, Arial Light, sans-serif; font-weight: 300;">S'inscrire</a>
                        </li>
                    <?php endif; ?>
                </ul>
			</div>
		</div>
	</nav>

    <main class="container">