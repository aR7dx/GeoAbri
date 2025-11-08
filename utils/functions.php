<?php

function setPageTitle(string $title): string {
    return $title . " - Équipements d'urgences";
}


// Fonctions de Requête SQL en dessous

/**
 * description: fonction qui permet de recuperer des equipements avec passant des filtres personalisés ou non
 */
function getEquipementsWithParameters($conn, string $ville, string $type_equipement, string $accessibilite_pmr, string $type_de_sol) {
    try {
        $sql = "SELECT * FROM GEO_EQUIPEMENT LIMIT 99";
        $stmt = preparerRequetePDO($conn, $sql);
        $stmt->execute();
        $donnees = array();
        LireDonneesPDOPreparee($stmt, $donnees);

        return $donnees;
    }
    catch (Exception $e) {
        return null;
    }
}

/**
 * description: fonction qui permet de recuperer le total du nombres d'equipements
 */
function getTotalEquipements($conn) {
    try {
        $sql = "SELECT count(*) as total FROM GEO_EQUIPEMENT";
        $stmt = preparerRequetePDO($conn, $sql);
        $stmt->execute();
        $donnees = array();
        LireDonneesPDOPreparee($stmt, $donnees);

        if (isset($donnees) && sizeof($donnees) == 1) {
            return $donnees[0]['total'];
        }
        else {
            return 0;
        }
    }
    catch (Exception $e){
        return 0;
    }
}

?>