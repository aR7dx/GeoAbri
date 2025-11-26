<?php

namespace App\Models\Map;

use PDO;
use PDOException;
use App\Config\Database;

class Suggestions {

    private Database $db;
    private array $filters;
    private int $limit;
    private int $max_limit = 75;
    private ?array $datas;

    public function __construct(array $_filters, int $_limit=-1)
    {
        $this->db = Database::getInstance();
        $this->filters = $_filters;
        $this->limit = $_limit;

        try 
        {
            $this->datas = $this->fetchSuggestions($this->filters, $this->limit);
        }
        catch (PDOException $e)
        {
            // TODO
        }
    }

    public function fetchSuggestions(array $filters, int $limit): array
    {
        if ($limit <= 0) { $limit = $this->max_limit; }

        $sql = "SELECT installation_numero as id, nom as name, commune, coordonnees_x as lon, coordonnees_y as lat FROM GEO_EQUIPEMENT ";

        if (isset($filters['query'])) {
            $sql .= "WHERE lower(nom) like '" . strtolower($filters['query']) . "%' ";
        }

        $sql .= "LIMIT " . $limit . ";";
        $stmt = $this->db->preparerRequetePDO($sql);
        return $this->db->LireDonneesPDOPreparee($stmt);
    }

    public function getDatas() 
    {
        return $this->datas;
    }

}