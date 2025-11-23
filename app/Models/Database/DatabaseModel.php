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
    private string $db_charset;

    private static ?DatabaseModel $instance = null;
    private PDO $connection;    

    public function __construct() {
        $envPath = dirname(dirname(dirname(__DIR__))) . '/.env';

        if (file_exists($envPath)) {
            try {
                $dotenv = Dotenv::createImmutable(dirname(dirname(dirname(__DIR__))));
                $dotenv->load();
            }
            catch (InvalidPathException $e) 
            {
                throw new EnvFileNotFoundException();
            }
        }

        $this->db_user = $_ENV["DB_USER"] ?? getenv("DB_USER");
        $this->db_password = $_ENV["DB_PASSWORD"] ?? getenv("DB_PASSWORD");
        $this->db_host = $_ENV["DB_HOST"] ?? getenv("DB_HOST");
        $this->db_port = $_ENV["DB_PORT"] ?? getenv("DB_PORT");
        $this->db_name = $_ENV["DB_NAME"] ?? getenv("DB_NAME");
        $this->db_charset = 'utf8mb4';

        try 
        {
            $this->connection = new PDO("mysql:host=". $this->db_host . ";port=" . $this->db_port . ";dbname=" . $this->db_name. ";charset=" . $this->db_charset, $this->db_user, $this->db_password);
        }
        catch (PDOException $e) 
        {
            throw new DatabaseConnectionException();
        }
    }

    public static function getInstance(): DatabaseModel {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): ?PDO {
        return $this->connection;
    }

    public function preparerRequetePDO(string $sql)
    {
        $db = self::getConnection() ?? throw new DatabaseConnectionException();
        return $db->prepare($sql);
    }

    public function majDonneesPrepareesPDO($cur, array $tab) 
    {
        return $cur->execute($tab);
    }

    public function LireDonneesPDOPreparee($stmt): array
    {
        $stmt->execute();
        $tab = $stmt->fetchall(PDO::FETCH_ASSOC);
        return $tab;
    }

}