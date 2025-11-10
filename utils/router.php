<?php

declare(strict_types=1);

class Router {

    //accueil
    public static string $accueil = '/';

    // carte-interactive
    public static string $carte_interactive = '/carte-interactive/';

    // compte
    public static string $compte = '/compte';

    // connexion
    public static string $connexion = '/connexion/connexion.php';
    public static string $inscription = '/connexion/inscription.php';

    // media
    public static string $media = '/media/';

    // utils
    public static string $header = __DIR__ . '/header.php';
    public static string $meta = __DIR__ . '/meta.php';
    public static string $footer = __DIR__ . '/footer.php';

    // utils/components
    public static string $notification = __DIR__ . '/components/notification.php';
    public static string $navbar = __DIR__ . '/components/navbar.php';

    // functions
    public static string $functions = __DIR__ . '/../functions/functions.php';

    // media
    public static string $logo_navbar = '/media/logo_normandie.jpg';

    // css
    public static string $css_style = '/assets/css/style.css';
    public static string $css_notification = '/assets/css/notification.css';

    //js
    public static string $js_leaflet = '/assets/js/leafletMap.js';
    
    // credits
    public static string $app_name = 'GeoAbri';


    /**
     * description: retourne la valeur de l'attribut passé en paramètre sous forme de string
     */
    public static function get(string $name): string {
        return self::$$name;
    }
}
?>