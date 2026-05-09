<?php

namespace App\Models\Map;

use PDO;
use PDOException;
use Database\Database;

class InteractiveMapModel {

    private Database $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }  

    public function getEquipementsByFilters(array $get, int $limit=750): array 
    {
        $minLat = floatval($get['minLat']);
        $maxLat = floatval($get['maxLat']);
        $minLon = floatval($get['minLon']);
        $maxLon = floatval($get['maxLon']);
        $query = (isset($get['q']) && !empty($get['q'])) ? strtolower(htmlspecialchars($get['q'])) : null;
        $id = (isset($get['id']) && !empty($get['id'])) ? htmlspecialchars($get['id']) : null;
        $category = (isset($get['category']) && !empty($get['category'])) ? strtolower(htmlspecialchars($get['category'])) : null;
        $pmr = (isset($get['pmr']) && !empty($get['pmr'])) ? strtolower(htmlspecialchars($get['pmr'])) : null;
        $etat = (isset($get['etat']) && !empty($get['etat'])) ? strtolower(htmlspecialchars($get['etat'])) : null;
        $activites = (isset($get['activites']) && !empty($get['activites'])) ? strtolower(htmlspecialchars($get['activites'])) : null;
        $acces_libre = (isset($get['acces_libre']) && !empty($get['acces_libre'])) ? strtolower(htmlspecialchars($get['acces_libre'])) : null;
        $commune = (isset($get['commune']) && !empty($get['commune'])) ? strtolower(htmlspecialchars($get['commune'])) : null;

        // Échantillonnage spatial avec dispersion uniforme sur toute la zone
        // Utilise un tri par hash des coordonnées pour simuler l'aléatoire rapidement
        $seed = mt_rand(1, 999999); // Seed aléatoire pour chaque requête
        $sql = "
            SELECT id, lon, lat FROM (
                SELECT 
                    installation_numero AS id, 
                    CAST(coordonnees_x AS DECIMAL(10,6)) AS lon, 
                    CAST(coordonnees_y AS DECIMAL(10,6)) AS lat,
                    @cell := CONCAT(FLOOR(coordonnees_y * 20), '_', FLOOR(coordonnees_x * 20)) AS cell_id,
                    @cell_rank := IF(@prev_cell = @cell, @cell_rank + 1, 1) AS cell_rank,
                    @prev_cell := @cell
                FROM (
                    SELECT * FROM GEO_EQUIPEMENT
                    WHERE coordonnees_x IS NOT NULL AND coordonnees_y IS NOT NULL
        ";
        
        // Ajouter tous les filtres existants
        if (isset($minLat, $maxLat, $minLon, $maxLon)) {
            $sql .= " AND coordonnees_y BETWEEN " . $minLat . " AND " . $maxLat . 
                    " AND coordonnees_x BETWEEN " . $minLon . " AND " . $maxLon;
        }

        if (!is_null($query)) {
            $sql .= " AND LOWER(nom) LIKE '%" . $query . "%'";
        }

        if (!is_null($category)) {
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
                $sql .= " AND (LOWER(activites) LIKE '%" . $category . "%' OR LOWER(type_famille) LIKE '%" .$category . "%')";
            }
        }

        if (!is_null($pmr)) {
            switch ($pmr) {
                case 'oui':
                    $sql .= " AND (acces_handi_mobilite IS NOT NULL OR acces_handi_sensoriel IS NOT NULL)";
                    break;
                case 'non':
                    $sql .= " AND (acces_handi_mobilite IS NULL AND acces_handi_sensoriel IS NULL)";
                    break;
            }
        }

        if (!is_null($etat)) {
            switch ($etat) {
                case 'valide':
                    $sql .= " AND etat = 'validé'";
                    break;
                case 'attente':
                    $sql .= " AND etat = 'en cours de modification'";
                    break;
            }
        }

        if (!is_null($activites)) {
            $sql .= " AND LOWER(activites) LIKE '%" . $activites . "%'";
        }

        if (!is_null($acces_libre)) {
            switch ($acces_libre) {
                case 'oui':
                    $sql .= " AND acces_libre = 'Oui'";
                    break;
                case 'non':
                    $sql .= " AND acces_libre = 'Non'";
                    break;
            }
        }

        if (!is_null($commune)) {
            $sql .= " AND lower(commune) like '%" . $commune . "%'";
        }

        $sql .= "
                    ORDER BY 
                        (CRC32(CONCAT(coordonnees_y, coordonnees_x, installation_numero, {$seed})) % 10000)
                ) AS randomized, (SELECT @cell := '', @cell_rank := 0, @prev_cell := '') AS vars
            ) AS ranked
            WHERE cell_rank <= 50
            LIMIT " . $limit;

        try
        {
            $stmt = $this->db->preparerRequetePDO($sql);
            $donnees = $this->db->LireDonneesPDOPreparee($stmt);
            return $donnees;
        }
        catch (PDOException $e) {
            throw new PDOException("Probleme avec la recuperation des equipements");
        }
    }
}