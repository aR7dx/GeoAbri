<?php

namespace App\Models\Map;

use App\Models\Database\DatabaseModel;
use PDO;

/**
 * The class follows the logic of the “Singleton” design pattern.
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
        $donnees = array();
        $this->db->LireDonneesPDOPreparee($stmt, $donnees);
        return $donnees;
    }

    public function getEquipementsByFilters(array $filters) {
        $sql = "SELECT coordonnees_x as longitude, coordonnees_y as latitude, nom as name, activites FROM GEO_EQUIPEMENT WHERE coordonnees_x IS NOT NULL AND coordonnees_y IS NOT NULL";

        // si les dimensions de la partie visible de la carte sont fournies on restreint les résultats à cette zone
        if (isset($filters['minLat'], $filters['maxLat'], $filters['minLon'], $filters['maxLon'])) {
            $sql .= " AND coordonnees_y BETWEEN " . $filters['minLat'] . " AND " . $filters['maxLat'] . 
                    " AND coordonnees_x BETWEEN " . $filters['minLon'] . " AND " . $filters['maxLon'];
        }

        // filtre optionnel par activité
        if (!empty($filters['activites'])) {
            $sql .= " AND activites LIKE '%" . $filters['activites'] . "%'";
        }

        // filtre optionnel par nom
        if (!empty($filters['search'])) {
            $sql .= " AND nom LIKE '%" . $filters['search'] . "%'";
        }

        // Limite de resultats par requête (5000 ca commence à beaucoup ralentir)
        $sql .= " LIMIT 1000";

        $stmt = $this->db->preparerRequetePDO($sql);
        $donnees = array();
        $this->db->LireDonneesPDOPreparee($stmt, $donnees);
        return $donnees;
    }

}