<?php

namespace App\Controllers\API;

use App\Models\Map\Suggestions;

class SuggestionsAPIController {
    public Suggestions $suggestions;

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

    public function transmitSuggestions($limit=-1) {
        $filters = [];
        if (isset($_GET['minLat'], $_GET['maxLat'], $_GET['minLon'], $_GET['maxLon'])) {
            $filters['minLat'] = floatval($_GET['minLat']);
            $filters['maxLat'] = floatval($_GET['maxLat']);
            $filters['minLon'] = floatval($_GET['minLon']);
            $filters['maxLon'] = floatval($_GET['maxLon']);
        }
        // Sert à prioriser les resultats par la ou on se trouve

        if (!empty($_GET['q'])) {
            $filters['query'] = $_GET['q'];
        }

        $suggestions = (new Suggestions($filters, $limit))->getDatas();

        echo json_encode($suggestions, JSON_UNESCAPED_UNICODE);
    }
}