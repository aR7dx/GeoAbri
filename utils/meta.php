<?php
    include_once 'router.php';
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="16x16" href="https://www.normandie.fr/profiles/createur_profil/themes/createur/favicon/favicon-16x16.png">
    <link rel="icon" type="image/png" sizes="32x32" href="https://www.normandie.fr/profiles/createur_profil/themes/createur/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" href="https://www.normandie.fr/profiles/createur_profil/themes/createur/favicon/favicon.ico">
    
    <?php //echo var_dump($_SERVER); ?>
    <title><?= (isset($titre)) ? $titre : $app_name ?></title>

    <!-- =====Leaflet===== -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
    <script defer src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script defer src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script defer src="<?= $js_leaflet ?>"></script>
    <!-- ================= -->

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="<?= $css_style ?>">
</head>