<?php

namespace App\Models\Map;

use PDO;
use App\Config\Database;

class InteractiveMapModel {

    private Database $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }
/*
    public function getActivities(): array
    {
        $sql = "SELECT DISTINCT activites FROM GEO_EQUIPEMENT WHERE activites not like '%,%' and activites not like '%/%' AND LENGTH(activites) <= 11 LIMIT 10;";
        $stmt = $this->db->preparerRequetePDO($sql);
        $donnees = $this->db->LireDonneesPDOPreparee($stmt);
        return $donnees;
    }
*/        

    public function getEquipementsByFilters(array $filters, int $limit=1000): array 
    {
        $sql = "SELECT installation_numero as id, coordonnees_x as longitude, coordonnees_y as latitude, nom as name
                FROM GEO_EQUIPEMENT WHERE coordonnees_x IS NOT NULL AND coordonnees_y IS NOT NULL";

        // si les dimensions de la partie visible de la carte sont fournies on restreint les résultats à cette zone
        if (isset($filters['minLat'], $filters['maxLat'], $filters['minLon'], $filters['maxLon'])) {
            $sql .= " AND coordonnees_y BETWEEN " . $filters['minLat'] . " AND " . $filters['maxLat'] . 
                    " AND coordonnees_x BETWEEN " . $filters['minLon'] . " AND " . $filters['maxLon'];
        }

        if (isset($filters['id'])) {
            $sql .= " AND installation_numero = '" . $filters['id'] . "'";
        }

        if (isset($filters['query'])) {
            $query = "%" . strtolower($filters['query']) . "%";
            $sql .= " AND LOWER(nom) LIKE '" . $query . "' ";
        }

        // Limite de resultats par requête (5000 ca commence à beaucoup ralentir)
        $sql .= " LIMIT " . $limit;

        $stmt = $this->db->preparerRequetePDO($sql);
        $donnees = $this->db->LireDonneesPDOPreparee($stmt);
        return $donnees;
    }
}