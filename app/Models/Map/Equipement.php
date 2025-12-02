<?php

namespace App\Models\Map;

use PDO;
use PDOException;
use App\Config\Database;
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

        if (!isset($post['name'], $post['commune'], $post['code_postal'], $post['adresse'], $post['description'])) {
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
            // Sdebut de la transaction
            $this->db->beginTransaction();

            // ajout de l'equipement dans la table GEO_EQUIPEMENT
            $sql = "INSERT INTO GEO_EQUIPEMENT (installation_numero, nom, creation_dt, maj_date, proprietaire_principal_nom, gestionnaire_type, mise_en_service_date, coordonnees_y, coordonnees_x, commune) 
                    VALUES (:id, :name, :date, :date_maj, :owner, :gest_type, :date_mise_service, :lat, :lon, :commune)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':id'   => $nextId,
                ':name' => $post['name'] ?? null,
                ':date' => date('Y-m-d H:i:s'),
                ':date_maj' => date('Y-m-d H:i:s'),
                ':owner' => $_SESSION['user']['email'],
                ':gest_type' => $_SESSION['user']['role'] ?? null,
                ':date_mise_service' => date('Y'),
                ':lat' => $lat,
                ':lon' => $lon,
                ':commune' => $post['commune'] ?? null
                // il faudrait inserer un type pour l'equipement car sinon cela affiche null dans le dashbaord
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
            return false;
        }
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