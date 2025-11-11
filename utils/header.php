<?php 

    include_once 'router.php';
    include_once 'pdo_agile.php';
    include_once  Router::$functions;
    include_once  Router::$notification;

    class Database {
        private const DB_USERNAME = "root";
        private const DB_PASSWORD = "";
        private const DB_HOST = "localhost";
        # private const DB_PORT = 3306;
        private const DB_NAME = "geoabri";

        public static function getDBUsername(): string {
            return self::DB_USERNAME;
        }

        public static function getDBPassword(): string {
            return self::DB_PASSWORD;
        }

        public static function getDBInfo(): string {
            return "mysql:host=" . self::DB_HOST .";dbname=" . self::DB_NAME . ";charset=utf8";
            # return "mysql:host=" . self::DB_HOST . ";port=" . self::DB_PORT . ";dbname=" . self::DB_NAME . ";charset=utf8";
        }
    }
    
    $conn = OuvrirConnexionPDO(Database::getDBInfo(),Database::getDBUsername(),Database::getDBPassword());
    
    
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

?>