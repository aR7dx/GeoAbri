<?php

namespace App\Controllers\Map;

use App\Models\Map\InteractiveMapModel;
use App\Models\Map\Equipement;
use App\Exceptions\Database\DatabaseConnectionException;

class InteractiveMapController {
    public ?InteractiveMapModel $mapModel;

    public function __construct() {
        try 
        {
            $this->mapModel = new InteractiveMapModel();
        }
        catch (DatabaseConnectionException $e)
        {
            $this->mapModel = null;
        }
    }

    public function index() {
        global $router;
        $titre = "Carte Interactive - GeoAbri";

        require dirname(__DIR__) . '/../Views/Map/interactive-map.php';
    }

    /**
     * Retourne un boolean pour savoir si l'url comporte ou non les chaines "http" et "https"
     */
    /*
    function urlNotContainHttpOrHttps(string $url): bool {
        return !empty($url) && !str_contains($url, 'http') && !str_contains($url, 'https');

        // TODO faire appel à une nouvelle méthode dans la base ou un nouveau model qui à une requete update 
        // qui corrige l'url en se servant de l'id $equipement['id']
    }*/
}