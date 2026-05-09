<?php

namespace App\Models\Map;

use PDO;
use PDOException;
use Database\Database;
use App\Exceptions\Database\DatabaseConnectionException;
use App\Middlewares\PermissionMiddleware;

class Equipement {

    private PDO $db;
    private array $datas;
    private ?string $id;

    public function __construct() {
        try
        {
            $this->db = Database::getInstance()->getConnection();
        }
        catch (PDOException $e) 
        {
            throw new DatabaseConnectionException();
        }
    }


    public function findById(?string $_id)
    {
        $this->id = $_id;

        try 
        {
            if ($this->id !== null) 
            {
                $sql = "SELECT * FROM GEO_EQUIPEMENT WHERE installation_numero = :id LIMIT 1;";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([':id' => $this->id]);
                $this->datas = $stmt->fetch(PDO::FETCH_ASSOC);
            }
            else 
            {
                $this->datas = [];
            }

            return $this->datas;
        }
        catch (PDOException $e)
        {
            // TODO
        }
    }

    public function nextId(): string {
        try
        {
            // on recupere l'id et on fait + 1
            $sql = "SELECT CONCAT(SUBSTRING(max_id, 1, 1), LPAD(CAST(SUBSTRING(max_id, 2) AS UNSIGNED) + 1, LENGTH(SUBSTRING(max_id, 2)), '0')) AS next_id 
                    FROM ( SELECT MAX(installation_numero) AS max_id FROM GEO_EQUIPEMENT ) AS t";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['next_id'] ?? '';
        }
        catch (PDOException $e)
        {
            return '';
        }
    }

    public function add (array $post): bool {
        PermissionMiddleware::handle("edit_equipement", ['redirect' => '/auth/login']);

        if (!isset($post['nom'], $post['commune'], $post['code_postal'], $post['adresse'], $post['description'])) {
            return false;
        }

        $fullAddress = $post['adresse'] . ", " . $post['code_postal'] . ", " . $post['commune'] . ", France";

        // contact de l'api pour recuperer les coordonnes du lieu
        $curl = curl_init("https://nominatim.openstreetmap.org/search?format=json&q=" . rawurlencode($fullAddress));
        curl_setopt_array($curl, [
            CURLOPT_USERAGENT => 'geoabri',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 3,
            CURLOPT_SSL_VERIFYPEER => false
        ]);
        $api_results = json_decode(curl_exec($curl), true);

        $lat = $api_results[0]['lat'] ?? null;
        $lon = $api_results[0]['lon'] ?? null;

        $nextId = $this->nextId();

        try
        {
            // debut de la transaction
            $this->db->beginTransaction();

            // Préparer les valeurs pour les champs
            $checkboxFields = ['aire_eclairage', 'douches', 'sanitaires', 'arrete_ouverture', 'acces_libre', 'ouverture_saisonniere'];
            foreach ($checkboxFields as $field) {
                if (!isset($post[$field]) || empty($post[$field])) {
                    $post[$field] = null;
                }
            }

            // ajout de l'equipement dans la table GEO_EQUIPEMENT
            $sql = "INSERT INTO GEO_EQUIPEMENT (
                        installation_numero, nom, type, creation_dt, maj_date, 
                        proprietaire_principal_nom, gestionnaire_type, mise_en_service_date, 
                        observations, coordonnees_y, coordonnees_x, commune,
                        nature, aire_nature_sol, chauffage_energie,
                        aire_longueur, aire_largeur, aire_hauteur, aire_surface,
                        vestiaires_sportifs_nb, vestiaires_arbitres_nb, places_tibune_nb,
                        aire_eclairage, douches, sanitaires, arrete_ouverture,
                        acces_handi_mobilite, acces_handi_sensoriel, acces_libre, ouverture_saisonniere,
                        capacite_actuelle, capacite_maximale, activites, website
                    ) VALUES (
                        :id, :nom, :type, :date, :date_maj,
                        :owner, :gest_type, :date_mise_service,
                        :observations, :lat, :lon, :commune,
                        :nature, :aire_nature_sol, :chauffage_energie,
                        :aire_longueur, :aire_largeur, :aire_hauteur, :aire_surface,
                        :vestiaires_sportifs_nb, :vestiaires_arbitres_nb, :places_tibune_nb,
                        :aire_eclairage, :douches, :sanitaires, :arrete_ouverture,
                        :acces_handi_mobilite, :acces_handi_sensoriel, :acces_libre, :ouverture_saisonniere,
                        :capacite_actuelle, :capacite_maximale, :activites, :website
                    )";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':id' => $nextId,
                ':nom' => htmlspecialchars($post['nom']),
                ':type' => htmlspecialchars($post['type'] ?? ''),
                ':date' => date('Y-m-d H:i:s'),
                ':date_maj' => date('Y-m-d H:i:s'),
                ':owner' => $_SESSION['user']['email'],
                ':gest_type' => $_SESSION['user']['role'] ?? null,
                ':date_mise_service' => date('Y'),
                ':observations' => htmlspecialchars($post['description'] ?? '') ?? null,
                ':lat' => $lat,
                ':lon' => $lon,
                ':commune' => htmlspecialchars($post['commune']),
                ':nature' => !empty($post['nature']) ? $post['nature'] : null,
                ':aire_nature_sol' => !empty($post['aire_nature_sol']) ? htmlspecialchars($post['aire_nature_sol']) : null,
                ':chauffage_energie' => !empty($post['chauffage_energie']) ? $post['chauffage_energie'] : null,
                ':aire_longueur' => !empty($post['aire_longueur']) ? floatval($post['aire_longueur']) : null,
                ':aire_largeur' => !empty($post['aire_largeur']) ? floatval($post['aire_largeur']) : null,
                ':aire_hauteur' => !empty($post['aire_hauteur']) ? floatval($post['aire_hauteur']) : null,
                ':aire_surface' => !empty($post['aire_surface']) ? floatval($post['aire_surface']) : null,
                ':vestiaires_sportifs_nb' => !empty($post['vestiaires_sportifs_nb']) ? intval($post['vestiaires_sportifs_nb']) : null,
                ':vestiaires_arbitres_nb' => !empty($post['vestiaires_arbitres_nb']) ? intval($post['vestiaires_arbitres_nb']) : null,
                ':places_tibune_nb' => !empty($post['places_tibune_nb']) ? intval($post['places_tibune_nb']) : null,
                ':aire_eclairage' => $post['aire_eclairage'] ?? null,
                ':douches' => $post['douches'] ?? null,
                ':sanitaires' => $post['sanitaires'] ?? null,
                ':arrete_ouverture' => $post['arrete_ouverture'] ?? null,
                ':acces_handi_mobilite' => !empty($post['acces_handi_mobilite']) ? $post['acces_handi_mobilite'] : null,
                ':acces_handi_sensoriel' => !empty($post['acces_handi_sensoriel']) ? $post['acces_handi_sensoriel'] : null,
                ':acces_libre' => $post['acces_libre'] ?? null,
                ':ouverture_saisonniere' => $post['ouverture_saisonniere'] ?? null,
                ':capacite_actuelle' => !empty($post['capacite_actuelle']) ? intval($post['capacite_actuelle']) : null,
                ':capacite_maximale' => !empty($post['capacite_maximale']) ? intval($post['capacite_maximale']) : null,
                ':activites' => htmlspecialchars($post['activites'] ?? ''),
                ':website' => htmlspecialchars($post['website'] ?? '')
            ]);

            // ajout de l'appartenance dans GEO_APPARTENIR
            $sql = "INSERT INTO GEO_APPARTENIR (user_id, installation_numero) VALUES (:user_id, :eq_id)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':user_id' => $_SESSION['user']['id'],
                ':eq_id' => $nextId
            ]);

            // commit seulement si les deux insert ont fonctionnés
            $this->db->commit();

            return true;
        }
        catch (PDOException $e)
        {
            // rollback si une transaction a été démarrée
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            // Log l'erreur pour debug
            error_log("Erreur insertion équipement: " . $e->getMessage());
            $_SESSION['debug_error'] = $e->getMessage();
            return false;
        }
    }

    public function edit(array $post): bool {
        PermissionMiddleware::handle("edit_equipement", ['redirect' => '/auth/login']);

        if (!isset($post['installation_numero'], $post['nom'], $post['commune'])) {
            return false;
        }

        $eq_id = htmlspecialchars($post['installation_numero']);

        try {
            // Vérification de l'équipement et de la permission
            $sql = "SELECT e.installation_numero, a.user_id
                    FROM GEO_EQUIPEMENT e
                    LEFT JOIN GEO_APPARTENIR a ON e.installation_numero = a.installation_numero
                    WHERE e.installation_numero = :eq_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':eq_id' => $eq_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) return false; // L'équipement n'existe pas

            // Vérification des permissions
            if (!in_array('edit_all_equipement', $_SESSION['user']['permissions']) && 
                $result['user_id'] != $_SESSION['user']['id']) {
                return false; // L'utilisateur n'a pas la permission de modifier cet équipement
            }

            $this->db->beginTransaction();

            // Préparer les valeurs pour les champs checkbox
            $checkboxFields = ['aire_eclairage', 'douches', 'sanitaires', 'arrete_ouverture', 'acces_libre', 'ouverture_saisonniere'];
            foreach ($checkboxFields as $field) {
                if (!isset($post[$field])) {
                    $post[$field] = 'Non';
                }
            }

            // Mise à jour de l'équipement
            $sql = "UPDATE GEO_EQUIPEMENT SET
                        nom = :nom,
                        type = :type,
                        description = :description,
                        maj_date = :maj_date,
                        commune = :commune,
                        coordonnees_y = :lat,
                        coordonnees_x = :lon,
                        proprietaire_principal_nom = :proprietaire_principal_nom,
                        proprietaire_principal_type = :proprietaire_principal_type,
                        gestionnaire_type = :gestionnaire_type,
                        gestion_dsp = :gestion_dsp,
                        nature = :nature,
                        aire_nature_sol = :aire_nature_sol,
                        chauffage_energie = :chauffage_energie,
                        aire_longueur = :aire_longueur,
                        aire_largeur = :aire_largeur,
                        aire_hauteur = :aire_hauteur,
                        aire_surface = :aire_surface,
                        vestiaires_sportifs_nb = :vestiaires_sportifs_nb,
                        vestiaires_arbitres_nb = :vestiaires_arbitres_nb,
                        places_tibune_nb = :places_tibune_nb,
                        aire_eclairage = :aire_eclairage,
                        douches = :douches,
                        sanitaires = :sanitaires,
                        arrete_ouverture = :arrete_ouverture,
                        autres_locaux = :autres_locaux,
                        acces_handi_mobilite = :acces_handi_mobilite,
                        acces_handi_sensoriel = :acces_handi_sensoriel,
                        acces_libre = :acces_libre,
                        ouverture_saisonniere = :ouverture_saisonniere,
                        capacite_actuelle = :capacite_actuelle,
                        capacite_maximale = :capacite_maximale,
                        erp_type = :erp_type,
                        erp_cat = :erp_cat,
                        activites = :activites,
                        utilisateurs = :utilisateurs,
                        website = :website,
                        observations = :observations,
                        type_famille = :type_famille
                    WHERE installation_numero = :eq_id";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':eq_id' => $eq_id,
                ':nom' => htmlspecialchars($post['nom']),
                ':type' => htmlspecialchars($post['type'] ?? ''),
                ':description' => htmlspecialchars($post['description'] ?? ''),
                ':maj_date' => date('Y-m-d H:i:s'),
                ':commune' => htmlspecialchars($post['commune']),
                ':lat' => $post['coordonnees_y'] ?? null,
                ':lon' => $post['coordonnees_x'] ?? null,
                ':proprietaire_principal_nom' => htmlspecialchars($post['proprietaire_principal_nom'] ?? ''),
                ':proprietaire_principal_type' => $post['proprietaire_principal_type'] ?? null,
                ':gestionnaire_type' => $post['gestionnaire_type'] ?? null,
                ':gestion_dsp' => $post['gestion_dsp'] ?? null,
                ':nature' => $post['nature'] ?? null,
                ':aire_nature_sol' => htmlspecialchars($post['aire_nature_sol'] ?? ''),
                ':chauffage_energie' => $post['chauffage_energie'] ?? null,
                ':aire_longueur' => $post['aire_longueur'] ?? null,
                ':aire_largeur' => $post['aire_largeur'] ?? null,
                ':aire_hauteur' => $post['aire_hauteur'] ?? null,
                ':aire_surface' => $post['aire_surface'] ?? null,
                ':vestiaires_sportifs_nb' => $post['vestiaires_sportifs_nb'] ?? null,
                ':vestiaires_arbitres_nb' => $post['vestiaires_arbitres_nb'] ?? null,
                ':places_tibune_nb' => $post['places_tibune_nb'] ?? null,
                ':aire_eclairage' => $post['aire_eclairage'],
                ':douches' => $post['douches'],
                ':sanitaires' => $post['sanitaires'],
                ':arrete_ouverture' => $post['arrete_ouverture'],
                ':autres_locaux' => htmlspecialchars($post['autres_locaux'] ?? ''),
                ':acces_handi_mobilite' => $post['acces_handi_mobilite'] ?? null,
                ':acces_handi_sensoriel' => $post['acces_handi_sensoriel'] ?? null,
                ':acces_libre' => $post['acces_libre'],
                ':ouverture_saisonniere' => $post['ouverture_saisonniere'],
                ':capacite_actuelle' => $post['capacite_actuelle'] ?? null,
                ':capacite_maximale' => $post['capacite_maximale'] ?? null,
                ':erp_type' => htmlspecialchars($post['erp_type'] ?? ''),
                ':erp_cat' => $post['erp_cat'] ?? null,
                ':activites' => htmlspecialchars($post['activites'] ?? ''),
                ':utilisateurs' => htmlspecialchars($post['utilisateurs'] ?? ''),
                ':website' => htmlspecialchars($post['website'] ?? ''),
                ':observations' => htmlspecialchars($post['observations'] ?? ''),
                ':type_famille' => htmlspecialchars($post['type_famille'] ?? '')
            ]);

            $this->db->commit();

            return true;
        } catch (PDOException $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            return false;
        }
    }

    public function delete(array $post): bool {
        PermissionMiddleware::handle("edit_equipement", ['redirect' => '/auth/login']);
        
        if (is_null($post['id'])) return false;

        $eq_id = htmlspecialchars(base64_decode($post['id']));

        try {
            // Vérification de l'équipement et de la permission
            $sql = "SELECT e.installation_numero, a.user_id
                    FROM GEO_EQUIPEMENT e
                    LEFT JOIN GEO_APPARTENIR a ON e.installation_numero = a.installation_numero
                    WHERE e.installation_numero = :eq_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':eq_id' => $eq_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) return false; // L'équipement n'existe pas

            // Vérification des permissions
            if (!in_array('edit_all_equipement', $_SESSION['user']['permissions']) && $result['user_id'] != $_SESSION['user']['id']) {
                return false; // L'utilisateur n'a pas la permission de supprimer cet équipement
            }

            $this->db->beginTransaction();

            // Supprimer l'appartenance à un équipement
            $sql = "DELETE FROM GEO_APPARTENIR WHERE installation_numero = :eq_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':eq_id' => $eq_id]);

            // Suppression de l'équipement
            $sql = "DELETE FROM GEO_EQUIPEMENT WHERE installation_numero = :eq_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':eq_id' => $eq_id]);

            $this->db->commit();

            return true;
        } catch (PDOException $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            return false;
        }

        return false;
    }

    public function getId() 
    {
        return $this->id;
    }

    public function getDatas() 
    {
        if (count($this->datas) > 0) 
        {
            return $this->datas[0];
        }
        return $this->datas;
    }

    public function getData(string $key) 
    {
        if (isset($this->datas[$key])) 
        {
            return $this->datas[$key];
        }
        return null;
    }

    // TODO
    // Peut etre remplacer la fonction getData ou pas et faire un getter par attribut dans le futur ?

}