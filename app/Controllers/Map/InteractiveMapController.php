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
}