<?php

if (!function_exists('asset')) {
    /**
     * Génère le chemin complet vers un asset statique
     * @param string $path - Le chemin du fichier relatif au dossier public
     * @return string - Le chemin complet de l'asset
     */
    function asset($path) {
        $path = ltrim($path, '/');
        
        // En mode développement avec php -S -t public
        if (php_sapi_name() === 'cli-server') {
            return "/{$path}";
        }
        
        return "/public/{$path}";
    }
} 