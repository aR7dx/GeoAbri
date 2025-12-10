<?php

namespace App\Controllers\API;

use App\Models\Map\FilterOptionsModel;

class FiltersAPIController {

    private FilterOptionsModel $filterOptionsModel;

    public function __construct() {
        $this->filterOptionsModel = new FilterOptionsModel();
    }

    /**
     * Retourne les options de filtres disponibles (catégories et activités)
     */
    public function index(): void 
    {
        header('Content-Type: application/json');
        header('Cache-Control: public, max-age=3600'); // Cache 1h car les données changent peu
        
        try {
            $categories = $this->filterOptionsModel->getCategories();
            $activites = $this->filterOptionsModel->getActivites();
            
            $response = [
                'categories' => $categories,
                'activites' => $activites
            ];
            
            echo json_encode($response);
            
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors de la récupération des options de filtres']);
        }
    }
}
