<?php

namespace App\Models\Admin;

use App\Models\Database\DatabaseModel;
use PDO;

/**
 * La classe suit la logique du patron de conception « Singleton ».
 */
class DataStatsModel {

    private DatabaseModel $db;

    public function __construct() {
        $this->db = new DatabaseModel();
    }

    public function getTotalEquipementsCount(): int 
    {
        $sql = "SELECT COUNT(*) as total FROM GEO_EQUIPEMENT;";
        $stmt = $this->db->preparerRequetePDO($sql);
        $donnees = $this->db->LireDonneesPDOPreparee($stmt);
        return (int)$donnees[0]['total'];
    }
}