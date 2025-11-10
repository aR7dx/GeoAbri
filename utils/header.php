<?php 

    include_once 'router.php';
    include_once 'pdo_agile.php';
    include_once  Router::$functions;
    include_once  Router::$notification;

    class Database {
        private const DB_USERNAME = "root";
        private const DB_PASSWORD = "";
        private const DB_HOST = "localhost";
        private const DB_NAME = "geoabri";

        public static function getDBUsername(): string {
            return self::DB_USERNAME;
        }

        public static function getDBPassword(): string {
            return self::DB_PASSWORD;
        }

        public static function getDBInfo(): string {
            return "mysql:host=" . self::DB_HOST .";dbname=" . self::DB_NAME . ";charset=utf8";
        }
    }
    
    // a mieux proteger par la suite car $conn peut etre modifié
    $conn = OuvrirConnexionPDO(Database::getDBInfo(),Database::getDBUsername(),Database::getDBPassword());
    
    
    session_start();

?>