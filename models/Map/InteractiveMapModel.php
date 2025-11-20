<?php

namespace App\Models\Map;

use App\Models\Database\DatabaseModel;
use PDO;

/**
 * La classe suit la logique du patron de conception « Singleton ».
 */
class InteractiveMapModel {

    private DatabaseModel $db;

    public function __construct() {
        $this->db = new DatabaseModel();
    }

    public function getActivities(): array
    {
        $sql = "SELECT DISTINCT activites FROM GEO_EQUIPEMENT WHERE activites not like '%,%' and activites not like '%/%' AND LENGTH(activites) <= 11 LIMIT 10;";
        $stmt = $this->db->preparerRequetePDO($sql);
        $donnees = $this->db->LireDonneesPDOPreparee($stmt);
        return $donnees;
    }

    public function getEquipement(string $id): array 
    {

        $sql = "SELECT * FROM GEO_EQUIPEMENT WHERE installation_numero = '" . $id . "' LIMIT 1;";
        $stmt = $this->db->preparerRequetePDO($sql);
        $donnees = $this->db->LireDonneesPDOPreparee($stmt);
        return $donnees;
    }

    public function getEquipementsByFilters(array $filters): array 
    {
        $sql = "SELECT coordonnees_x as longitude, coordonnees_y as latitude, nom as name, activites, installation_numero as id 
                FROM GEO_EQUIPEMENT WHERE coordonnees_x IS NOT NULL AND coordonnees_y IS NOT NULL";

        // si les dimensions de la partie visible de la carte sont fournies on restreint les résultats à cette zone
        if (isset($filters['minLat'], $filters['maxLat'], $filters['minLon'], $filters['maxLon'])) {
            $sql .= " AND coordonnees_y BETWEEN " . $filters['minLat'] . " AND " . $filters['maxLat'] . 
                    " AND coordonnees_x BETWEEN " . $filters['minLon'] . " AND " . $filters['maxLon'];
        }

        if (isset($filters['query'])) {
            $query = "%" . strtolower($filters['query']) . "%";
            $sql .= " AND (LOWER(nom) LIKE '" . $query . "' OR LOWER(activites) LIKE '" . $query . "')";
        }

        // Limite de resultats par requête (5000 ca commence à beaucoup ralentir)
        $sql .= " LIMIT 1000";

        $stmt = $this->db->preparerRequetePDO($sql);
        $donnees = $this->db->LireDonneesPDOPreparee($stmt);
        return $donnees;
    }

    public function getSearchSuggestions(string $query, int $limit=-1): array 
    {   
        if ($limit <= 0) { $limit = random_int(5, 25); }

        $query = "%" . $query . "%";
        //$sql = "SELECT installation_numero as id, nom as name, activites FROM GEO_EQUIPEMENT
        //        WHERE LOWER(nom) LIKE '" . $query . "' OR LOWER(activites) LIKE '" . $query . "' LIMIT 7";
        
        $sql = "SELECT installation_numero as id, nom as name, activites, commune FROM GEO_EQUIPEMENT
                WHERE LOWER(nom) LIKE '" . $query . "' LIMIT " . $limit;

        $stmt = $this->db->preparerRequetePDO($sql);
        $donnees = $this->db->LireDonneesPDOPreparee($stmt);
        return $donnees;
    }

}