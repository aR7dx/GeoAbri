<?php

namespace App\Models\Map;

use PDO;
use PDOException;
use App\Config\Database;

class Suggestions {

    private Database $db;
    private array $filters;
    private int $limit;
    private int $offset;
    private int $max_limit = 75;
    private ?array $datas;
    private int $totalCount;

    public function __construct(array $_filters, int $_limit=-1, int $offset = 0)
    {
        $this->db = Database::getInstance();
        $this->filters = $_filters;
        $this->limit = $_limit;
        $this->offset = $offset;

        try 
        {
            $this->datas = $this->fetchSuggestions($this->filters, $this->limit, $this->offset);
            $this->totalCount = $this->totalCount($this->filters);
        }
        catch (PDOException $e)
        {
            $this->datas = [];
        }
    }

    public function fetchSuggestions(array $filters, int $limit, int $offset): array
    {
        if ($limit <= 0) { $limit = $this->max_limit; }
        
        $sql = "SELECT eq.installation_numero as id, eq.nom as name, observations, commune, coordonnees_x as lon, coordonnees_y as lat, type, gestionnaire_type as owner, 
                website, email
                FROM GEO_EQUIPEMENT eq
                LEFT JOIN GEO_APPARTENIR app on eq.installation_numero = app.installation_numero
                LEFT JOIN GEO_UTILISATEURS u on app.user_id = u.user_id ";

        if (isset($filters['query']) && $filters['query'] !== "") {
            $sql .= "WHERE lower(eq.nom) like '" . strtolower($filters['query']) . "%' OR lower(eq.commune) like '" . strtolower($filters['query']) . "%' OR lower(eq.installation_numero) like '" . strtolower($filters['query']) . "%' ";
        }

        $sql .= "LIMIT " . $limit . " OFFSET " . $offset;

        $stmt = $this->db->preparerRequetePDO($sql);
        return $this->db->LireDonneesPDOPreparee($stmt);
    }

    public function totalCount(array $filters) {
        $sql = "SELECT count(*) as total FROM GEO_EQUIPEMENT ";

        if (isset($filters['query']) && $filters['query'] !== "") {
            $sql .= "WHERE lower(nom) like '" . strtolower($filters['query']) . "%' OR lower(commune) like '" . strtolower($filters['query']) . "%' OR lower(installation_numero) like '" . strtolower($filters['query']) . "%' ";
        }

        $stmt = $this->db->preparerRequetePDO($sql);
        $result = $this->db->LireDonneesPDOPreparee($stmt);

        return intval($result[0]['total'] ?? 0);
    }

    public function getDatas() 
    {
        return $this->datas;
    }

    public function getTotalCount() {
        return $this->totalCount;
    }
}