<?php

namespace App\Models\Map;

use PDO;
use App\Config\Database;

class FilterOptionsModel {

    private Database $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }  

    /**
     * Récupère toutes les catégories d'équipements avec leur nombre d'occurrences
     * Basé sur le champ type_famille
     */
    public function getCategories(): array 
    {
        $sql = "
            SELECT 
                type_famille AS category,
                COUNT(*) AS count
            FROM GEO_EQUIPEMENT
            WHERE type_famille IS NOT NULL 
                AND type_famille != ''
                AND coordonnees_x IS NOT NULL 
                AND coordonnees_y IS NOT NULL
            GROUP BY type_famille
            ORDER BY count DESC, type_famille ASC
        ";

        try {
            $stmt = $this->db->preparerRequetePDO($sql);
            $donnees = $this->db->LireDonneesPDOPreparee($stmt);
            return $donnees;
        } catch (PDOException $e) {
            throw new PDOException("Problème avec la récupération des catégories");
        }
    }

    /**
     * Récupère toutes les activités avec leur nombre d'occurrences
     * Parse le champ activites qui contient plusieurs activités séparées
     */
    public function getActivites(): array 
    {
        $sql = "
            SELECT 
                activites,
                COUNT(*) AS count
            FROM GEO_EQUIPEMENT
            WHERE activites IS NOT NULL 
                AND activites != ''
                AND coordonnees_x IS NOT NULL 
                AND coordonnees_y IS NOT NULL
            GROUP BY activites
            ORDER BY count DESC
            LIMIT 100
        ";

        try {
            $stmt = $this->db->preparerRequetePDO($sql);
            $donnees = $this->db->LireDonneesPDOPreparee($stmt);
            
            // Parser les activités multiples et les compter
            $activitesCount = [];
            
            foreach ($donnees as $row) {
                $activitesList = explode(',', $row['activites']);
                foreach ($activitesList as $activite) {
                    $activite = trim($activite);
                    if (!empty($activite)) {
                        if (!isset($activitesCount[$activite])) {
                            $activitesCount[$activite] = 0;
                        }
                        $activitesCount[$activite] += intval($row['count']);
                    }
                }
            }
            
            // Convertir en tableau associatif et trier
            arsort($activitesCount);
            
            // Convertir au format attendu
            $result = [];
            foreach ($activitesCount as $activite => $count) {
                $result[] = [
                    'activite' => $activite,
                    'count' => $count
                ];
            }
            
            return array_slice($result, 0, 50); // Limiter aux 50 activités les plus fréquentes
            
        } catch (PDOException $e) {
            throw new PDOException("Problème avec la récupération des activités");
        }
    }
}
