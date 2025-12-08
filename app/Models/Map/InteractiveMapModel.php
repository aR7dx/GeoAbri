<?php

namespace App\Models\Map;

use PDO;
use App\Config\Database;

class InteractiveMapModel {

    private Database $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }  

    public function getEquipementsByFilters(array $filters, int $limit=750): array 
    {
        $sql = "SELECT installation_numero as id, coordonnees_x as longitude, coordonnees_y as latitude
                FROM GEO_EQUIPEMENT WHERE coordonnees_x IS NOT NULL AND coordonnees_y IS NOT NULL";

        // Filtrage par zone visible sur la carte
        if (isset($filters['minLat'], $filters['maxLat'], $filters['minLon'], $filters['maxLon'])) {
            $sql .= " AND coordonnees_y BETWEEN " . $filters['minLat'] . " AND " . $filters['maxLat'] . 
                    " AND coordonnees_x BETWEEN " . $filters['minLon'] . " AND " . $filters['maxLon'];
        }

        // Filtrage par recherche textuelle
        if (isset($filters['query'])) {
            $query = "%" . strtolower($filters['query']) . "%";
            $sql .= " AND LOWER(nom) LIKE '" . $query . "'";
        }

        // Filtrage par catégorie
        if (isset($filters['category']) && !empty($filters['category'])) {
            $category = strtolower($filters['category']);

            // Gestion des catégories de l'accueil qui sont des regroupement de catégories
            $categoryMapping = [
                'terrain' => ['foot', 'rugby', 'athletisme', 'terrain', 'basket', 'hand'],
                'aquatique' => ['natation', 'waterpolo', 'piscine', 'piscines', 'aqua'],
                'specialise' => ['tennis', 'skate', 'escalade', 'patinage', 'ping']
            ];

            if (array_key_exists($category, $categoryMapping)) {
                $subCategories = $categoryMapping[$category];
                $sql .= " AND (LOWER(activites) LIKE '%" . implode("%' OR LOWER(activites) LIKE '%", $subCategories) . "%' OR LOWER(type_famille) LIKE '%" . implode("%' OR LOWER(type_famille) LIKE '%", $subCategories) . "%')";
            } elseif ($category === "exterieur") {
                $sql .= " AND (upper(erp_type) = 'PA' OR lower(activites) LIKE '%arbre%' OR lower(activites) LIKE '%exterieur%' OR lower(type_famille) LIKE '%arbre%' OR lower(type_famille) LIKE '%exterieur%')";
            } else {
                // recherche si ce n'est pas une categorie regroupante
                $sql .= " AND (LOWER(activites) LIKE '%" . $category . "%' OR LOWER(type_famille) LIKE '%" .$category . "%')";
            }
        }

        $sql .= " LIMIT " . $limit;

        $stmt = $this->db->preparerRequetePDO($sql);
        $donnees = $this->db->LireDonneesPDOPreparee($stmt);
        return $donnees;
    }
}