<?php

namespace App\Exceptions\Dotenv;

use Exception;

class EnvFileNotFoundException extends Exception {
    public function __construct(string $message = "Impossible de trouver le fichier d'environnement.") {
        parent::__construct($message);
    }
}