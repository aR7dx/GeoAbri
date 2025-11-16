<?php

namespace App\Models\Database;

use PDO;
use PDOException;
use Exception;
use App\Exceptions\Database\DatabaseConnectionException;

/**
 * The class follows the logic of the “Singleton” design pattern.
 */
class DatabaseModel {

    private const DB_USER = "root";
    private const DB_PASSWORD = "";
    private const DB_HOST = "localhost";
    private const DB_PORT = "3306"; // no necessary to change this normally
    private const DB_NAME = "geoabri";

    private static ?PDO $instance = null;

    public static function getInstance(): ?PDO {
        if (self::$instance === null) {
            try 
            {
                self::$instance = new PDO("mysql:host=". self::DB_HOST . ";dbname=" . self::DB_NAME . ";charset=utf8", self::DB_USER, self::DB_PASSWORD);
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