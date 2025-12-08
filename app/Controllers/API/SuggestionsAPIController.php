<?php

namespace App\Controllers\API;

use App\Models\Map\Suggestions;

class SuggestionsAPIController {
    public Suggestions $suggestions;

    public function index() {
        global $router;

        if (isset($_GET['page']) && !empty($_GET['page'])) {
            $this->suggestionsByQueryAndPaginated();
            exit;
        }
        elseif (isset($_GET['q']) && !empty($_GET['q'])) {
            $this->suggestionsByQuery();
            exit;
        }
        else {
            header('Location: ' . $router->generate('api'));
            exit;
        }

        return;
    }

    public function suggestionsByQueryAndPaginated($limit=12) {
        $filters = [];
        $page = max(1, intval($_GET['page'])) ?? 1;
        $offset = ($page - 1) * $limit;

        if (isset($_GET['q']) && !empty($_GET['q'])) {
            $filters['query'] = htmlspecialchars($_GET['q']);
        }

        $suggestionsModel = new Suggestions($filters, $limit, $offset);
        $suggestions = $suggestionsModel->getDatas();
        $totalCount = $suggestionsModel->getTotalCount();

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            "page" => $page,
            "limit" => $limit,
            "count" => count($suggestions),
            "total_count" => $totalCount,
            "suggestions" => $suggestions
        ], JSON_UNESCAPED_UNICODE);
    }

    public function suggestionsByQuery($limit=75) {
        $filters = [];
        if (isset($_GET['minLat'], $_GET['maxLat'], $_GET['minLon'], $_GET['maxLon'])) {
            $filters['minLat'] = floatval($_GET['minLat']);
            $filters['maxLat'] = floatval($_GET['maxLat']);
            $filters['minLon'] = floatval($_GET['minLon']);
            $filters['maxLon'] = floatval($_GET['maxLon']);
        }
        // Sert à prioriser les resultats par la ou on se trouve

        if (isset($_GET['q']) && !empty($_GET['q']) && $_GET['q'] !== 'null') {
            $filters['query'] = htmlspecialchars($_GET['q']);
        }

        $suggestions = (new Suggestions($filters, $limit))->getDatas();

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($suggestions, JSON_UNESCAPED_UNICODE);
    }
}