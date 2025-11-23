<?php

namespace App\Controllers\API;

use App\Models\Map\InteractiveMapModel;

class SuggestionsAPIController {
    public InteractiveMapModel $mapModel;

    public function __construct() {
        $this->mapModel = new InteractiveMapModel();
    }

    public function index() {
        global $router;
        
        if (isset($_GET['q']) && !empty($_GET['q'])) {
            $this->transmitSuggestions();
            exit;
        }
        else {
            header('Location: /');
            exit;
        }

        return;
    }

    public function transmitSuggestions() {


        //strtolower($_GET['q'])
        echo "suggestions";
    }
}