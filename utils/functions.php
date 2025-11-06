<?php

function setPageTitle(string $title): string {
    return $title . " - Équipements d'urgences";
}

function getEquipementsWithParameters($conn, string $ville, string $type_equipement, string $accessibilite_pmr, string $type_de_sol) {
    try {
        $sql = "SELECT * FROM GEO_EQUIPEMENT LIMIT 10";
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

?>