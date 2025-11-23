<?php

namespace App\Models\Map;

use PDO;
use PDOException;
use App\Config\Database;

class Equipement {

    private Database $db;
    private array $datas;
    private ?string $id;

    public function __construct(?string $_id)
    {
        $this->db = Database::getInstance();
        $this->id = $_id;

        try 
        {
            if ($this->id !== null) 
            {
                $sql = "SELECT * FROM GEO_EQUIPEMENT WHERE installation_numero = '" . $this->id . "' LIMIT 1;";
                $stmt = $this->db->preparerRequetePDO($sql);
                $this->datas = $this->db->LireDonneesPDOPreparee($stmt);
            }
            else 
            {
                $this->datas = [];
            }
        }
        catch (PDOException $e)
        {
            // TODO
        }
    }

    public function getId() 
    {
        return $this->id;
    }

    public function getDatas() 
    {
        if (count($this->datas) > 0) 
        {
            return $this->datas[0];
        }
        return $this->datas;
    }

    public function getData(string $key) 
    {
        if (isset($this->datas[$key])) 
        {
            return $this->datas[$key];
        }
        return null;
    }

    // TODO
    // Peut etre remplacer la fonction getData ou pas et faire un getter par attribut dans le futur ?

}