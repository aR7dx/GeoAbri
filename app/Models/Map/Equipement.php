<?php

namespace App\Models\Map;

use PDO;
use PDOException;
use App\Config\Database;

class Equipement {

    private PDO $db;
    private array $datas;
    private ?string $id;

    public function __construct() {
        try
        {
            $this->db = Database::getInstance()->getConnection();
        }
        catch (PDOException $e) 
        {
            throw new DatabaseConnectionException();
        }
    }


    public function findById(?string $_id)
    {
        $this->id = $_id;

        try 
        {
            if ($this->id !== null) 
            {
                $sql = "SELECT * FROM GEO_EQUIPEMENT WHERE installation_numero = :id LIMIT 1;";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([':id' => $this->id]);
                $this->datas = $stmt->fetch(PDO::FETCH_ASSOC);
            }
            else 
            {
                $this->datas = [];
            }

            return $this->datas;
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