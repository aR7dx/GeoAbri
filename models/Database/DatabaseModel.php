<?php

namespace App\Models\Database;

use PDO;
use PDOException;
use Exception;
use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;
use App\Exceptions\Dotenv\EnvFileNotFoundException;
use App\Exceptions\Database\DatabaseConnectionException;

/**
 * The class follows the logic of the “Singleton” design pattern.
 */
class DatabaseModel {

    private string $db_user;
    private string $db_password;
    private string $db_host;
    private string $db_port;
    private string $db_name;

    private static ?PDO $instance = null;

    public function __construct() {
        try {
            $dotenv = Dotenv::createImmutable(dirname(dirname(__DIR__)));
            $dotenv->load();
        }
        catch (InvalidPathException $e) 
        {
            throw new EnvFileNotFoundException();
        }

        $this->db_user = $_ENV["DB_USER"];
        $this->db_password = $_ENV["DB_PASSWORD"];
        $this->db_host = $_ENV["DB_HOST"];
        $this->db_port = $_ENV["DB_PORT"];
        $this->db_name = $_ENV["DB_NAME"];
    }

    public static function getInstance(): ?PDO {
        if (self::$instance === null) {
            try 
            {
                $db = new self();
                self::$instance = new PDO("mysql:host=". $db->db_host . ";port=" . $db->db_port . ";dbname=" . $db->db_name. ";charset=utf8", $db->db_user, $db->db_password);
            } 
            catch (PDOException $e) 
            {
                throw new DatabaseConnectionException();
            }
        }

        return self::$instance;
    }

    public static function preparerRequetePDO(string $sql)
    {
        $db = self::getInstance() ?? throw new DatabaseConnectionException();
        return $db->prepare($sql);
    }

    public static function majDonneesPrepareesPDO($cur, array $tab) 
    {
        return $cur->execute($tab);
    }

    public static function LireDonneesPDOPreparee($stmt, &$tab) 
    {
        $stmt->execute();
        $tab = $stmt->fetchall(PDO::FETCH_ASSOC);
        return count($tab);
    }

}