<?php

namespace App\Controllers\Account;

use App\Middlewares\AuthMiddleware;

class EditAccountController {

    public function index() {
        AuthMiddleware::handle();

        global $router;
        $titre = "Modifier mon compte - GeoAbri";

        require dirname(__DIR__) . '/../Views/Account/edit.php';
    }
}