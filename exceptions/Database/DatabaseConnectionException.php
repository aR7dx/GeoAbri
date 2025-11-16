<?php

namespace App\Exceptions\Database;

use Exception;

class DatabaseConnectionException extends Exception {
    public function __construct(string $message = "La connexion à la base de données est impossible.") {
        parent::__construct($message);
    }
}