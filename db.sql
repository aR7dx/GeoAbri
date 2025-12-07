-- -----------------------------------------------------------------------------
--             Génération d'une base de données pour MySQL
-- -----------------------------------------------------------------------------
--      Nom de la base : geoabri
--      Projet : geoabri  (GeoAbri)
-- -----------------------------------------------------------------------------

-- https://equipements-sportsgouv.contribuer.io/tables/FkRMPoloNHvPiJjz3a2rQdVMmrrfbgD-ePyz9VJfN5o/contributions/2099378/new



DROP TABLE IF EXISTS GEO_EQUIPEMENT;
DROP TABLE IF EXISTS GEO_PERSONNE;
DROP TABLE IF EXISTS GEO_TYPE_PERSONNE;
DROP TABLE IF EXISTS GEO_PLANNING;


CREATE TABLE IF NOT EXISTS GEO_EQUIPEMENT 
(
	installation_numero VARCHAR(10),
	installation_id VARCHAR(4), --  souvent vide
 	creation_dt DATE, --  2025-03-31
	maj_date DATE, --  2025-03-31
 	maj_lien VARCHAR(175),
 	numero VARCHAR(14), --  E024I212310064
 	nom VARCHAR(100), --  Courts de tennis couverts 5
 	type VARCHAR(100), --  Court de tennis
	description VARCHAR(200), 
 	coordonnees VARCHAR(40), --  47.31406, 5.08352
 	proprietaire_principal_nom VARCHAR(250), --  SYNDICAT INTERDEPARTEMENTAL POUR LA GESTION DES PARCS DE SPORTS DE BOBIGNY ET LA COURNEUVE (SIGPS)
 	proprietaire_principal_type VARCHAR(100), --  Région
 	autres_proprietaires VARCHAR(255),
 	proprietaire_secondaire_type VARCHAR(100), --  Etat
 	gestionnaire_type VARCHAR(100), --  Etablissement Public
 	co_gestionnaire_type VARCHAR(100),
 	gestion_dsp VARCHAR(3), --  Non ou Oui
 	arrete_ouverture VARCHAR(3), --  Oui ou Non
 	erp_type VARCHAR(25),  --  RPE,CTS,X,R
 	erp_cat INT, --  1,2,3,4 ou 5
 	is_date_homologation_known TINYINT(1), --  1 ou 0
 	homologation_date DATE, --  14/06/2006
 	homologation_periode VARCHAR(30), -- 1975-1984
 	is_date_mise_en_service_known TINYINT(1), -- 1 ou 0
 	mise_en_service_date VARCHAR(4), -- 2004
 	mise_en_service_periode VARCHAR(100), --  à partir de 2004
 	is_date_derniers_travaux_known TINYINT(1), -- 1 ou 0
 	derniers_travaux_date VARCHAR(4), -- 2004
 	derniers_travaux_periode VARCHAR(100), -- ?
 	derniers_travaux_type VARCHAR(255), -- ?
 	chauffage_energie VARCHAR(50), -- Electricité,etc
 	nature VARCHAR(50), --  Decouvert
 	aire_nature_sol VARCHAR(50), -- Beton
 	aire_longueur DECIMAL(10,1), -- 12.0
 	aire_largeur DECIMAL(10,1), -- 6
 	aire_hauteur DECIMAL(10,1), -- 6
 	aire_surface DECIMAL(10,1), -- 72
 	aire_eclairage VARCHAR(3), -- Oui ou Non
 	aire_couloirs_nb INT,
 	places_tibune_nb DECIMAL(10,1), -- 0.0
 	vestiaires_sportifs_nb DECIMAL(5,1), -- 2.0
 	vestiaires_arbitres_nb DECIMAL(3,1), -- 0.0
 	douches VARCHAR(3), -- Oui ou Non
 	sanitaires VARCHAR(3), -- Oui ou Non
 	autres_locaux TEXT, -- Réception / Accueil, Bureau(x) Club(s), Buvette, Club(s) house, Local de rangement, Salle(s) de réunion/cours
 	amenagements_confort TEXT,
 	acces_handi_mobilite VARCHAR(100), -- Aire de jeu
 	acces_handi_sensoriel VARCHAR(100), -- Aire de jeu
 	is_pdesi_pdipr VARCHAR(3),
 	bassin_longueur DECIMAL(10,2), -- 50
 	bassin_largeur DECIMAL(10,2), -- 21
 	bassin_surface DECIMAL(10,2), -- 1050
 	bassin_profondeur_min DECIMAL(10,2), -- 1.2
 	bassin_profondeur_max DECIMAL(10,2), -- 2.1
 	piste_longueur DECIMAL(10,2),
 	sae_hauteur DECIMAL(10,2),
 	sae_surface DECIMAL(10,2),
 	sae_couloirs_nb INT,
 	pas_de_tir_type VARCHAR(100),
 	website VARCHAR(255), -- https://parcs-sports-75-93.fr/
 	utilisateurs VARCHAR(255), -- Clubs sportifs, comités, ligues, fédérations
 	acces_libre VARCHAR(3), -- Oui ou Non
 	ouverture_saisonniere VARCHAR(3), -- Oui ou Non
 	activites VARCHAR(500), -- Tennis, Backet-Ball, ...
 	observations TEXT,
 	coordonnees_y DECIMAL(10,6), -- 45.7535 (latitude)
 	coordonnees_x DECIMAL(10,6), --  -0.647111 (longitude)
 	activites_code VARCHAR(100), -- 7901, 8101, 8103
 	activites_json JSON, -- avec autre api
 	completion_taux INT, -- 75 (%)
 	equip_nb INT, -- 23
 	equipement_id VARCHAR(7), -- 9e2651b
 	etat VARCHAR(30), -- Validé
 	type_famille VARCHAR(50), -- Court de tennis
 	type_code VARCHAR(4), -- 1402
 	rnb_id VARCHAR(50), -- NM2R8T1HJ3BF
 	commune VARCHAR(255) --  Saintes
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Clé primaire pour GEO_EQUIPEMENT
ALTER TABLE GEO_EQUIPEMENT
ADD CONSTRAINT pk_geo_equipement
PRIMARY KEY (installation_numero);

CREATE TABLE IF NOT EXISTS GEO_UTILISATEURS
(
	user_id INT AUTO_INCREMENT PRIMARY KEY,
	nom VARCHAR(50),
	prenom VARCHAR(50),
	email VARCHAR(255) NOT NULL UNIQUE,
	telephone VARCHAR(14),
	ville VARCHAR(100),
	code_postal VARCHAR(5),
	password_hash VARCHAR(255) NOT NULL,
	role_id INT DEFAULT NULL,
	is_active TINYINT(1) DEFAULT 1,
	created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
	updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE IF NOT EXISTS GEO_ROLES 
(
  role_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE,
  description VARCHAR(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS GEO_PERMISSIONS 
(
  permission_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE,
  description VARCHAR(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS GEO_ROLE_PERMISSIONS 
(
  role_id INT NOT NULL,
  permission_id INT NOT NULL,
  PRIMARY KEY (role_id, permission_id),
  FOREIGN KEY (role_id) REFERENCES GEO_ROLES(role_id) ON DELETE CASCADE,
  FOREIGN KEY (permission_id) REFERENCES GEO_PERMISSIONS(permission_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE IF NOT EXISTS GEO_PLANNING 
(
	id_rdv INT,
	user_id INT,
	installation_numero VARCHAR(10),
	PLA_date DATE,
	PLA_heure_debut TIME,
	PLA_heure_fin TIME
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Clé primaire pour GEO_PLANNING
ALTER TABLE GEO_PLANNING
ADD CONSTRAINT pk_geo_planning
PRIMARY KEY (id_rdv);

CREATE TABLE IF NOT EXISTS GEO_APPARTENIR 
(
	user_id INT,
	installation_numero VARCHAR(10)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Clé primaire pour GEO_APPARTENIR
ALTER TABLE GEO_APPARTENIR
ADD CONSTRAINT pk_geo_appartenir
PRIMARY KEY (user_id, installation_numero);

CREATE TABLE IF NOT EXISTS GEO_ALERTES
(
	id_alerte INT AUTO_INCREMENT PRIMARY KEY,
	nom VARCHAR(50),
	description VARCHAR(300),
	date_debut DATE,
	date_fin DATE DEFAULT NULL,
	niveau INT,
	propietaire_id INT,
	ville VARCHAR(100),
	lat DECIMAL(10,6),
	lon DECIMAL(10,6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS GEO_DEMANDES
(
	id_demande INT AUTO_INCREMENT PRIMARY KEY,
	id_type_demande INT NOT NULL,
	nom VARCHAR(50) NOT NULL,
	description VARCHAR(300) NOT NULL,
	date_debut DATE NOT NULL,
	date_fin DATE DEFAULT NULL,
	demandeur_id INT NOT NULL,
	status VARCHAR(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS GEO_TYPE_DEMANDES
(
	id_type_demande INT AUTO_INCREMENT PRIMARY KEY,
	nom VARCHAR(50) NOT NULL,
	description VARCHAR(100) NOT NULL,
	alias VARCHAR(25) NOT NULL
)



-- -----------------------------------------------------------------------------
--             Création des clés étrangères
-- -----------------------------------------------------------------------------


-- Relation entre GEO_UTILISATEURS et GEO_TYPE_UTILISATEURS
/*
ALTER TABLE GEO_UTILISATEURS
ADD CONSTRAINT fk_personne_type_utilisateurs
FOREIGN KEY (user_type_id)
REFERENCES GEO_TYPE_UTILISATEURS(user_type_id);*/

-- Relation entre GEO_PLANNING et GEO_UTILISATEURS
ALTER TABLE GEO_PLANNING
ADD CONSTRAINT fk_planning_utilisateurs
FOREIGN KEY (user_id)
REFERENCES GEO_UTILISATEURS(user_id);

-- Relation entre GEO_PLANNING et GEO_EQUIPEMENT
ALTER TABLE GEO_PLANNING
ADD CONSTRAINT fk_planning_equipement
FOREIGN KEY (installation_numero)
REFERENCES GEO_EQUIPEMENT(installation_numero);

-- Relation entre GEO_APPARTENIR
ALTER TABLE GEO_APPARTENIR
ADD CONSTRAINT fk_utilisateurs_appartenir
FOREIGN KEY (user_id)
REFERENCES GEO_UTILISATEURS(user_id);

ALTER TABLE GEO_APPARTENIR
ADD CONSTRAINT fk_equipement_appartenir
FOREIGN KEY (installation_numero)
REFERENCES GEO_EQUIPEMENT(installation_numero);

-- Relation entre GEO_ALERTES et GEO_UTILISATEURS
ALTER TABLE GEO_ALERTES
ADD CONSTRAINT fk_alerte_utilisateur
FOREIGN KEY (propietaire_id)
REFERENCES GEO_UTILISATEURS(user_id);

-- Relation entre GEO_DEMANDES et GEO_UTILISATEURS
ALTER TABLE GEO_DEMANDES
ADD CONSTRAINT fk_demande_utilisateur
FOREIGN KEY (demandeur_id)
REFERENCES GEO_UTILISATEURS(user_id);

-- Relation entre GEO_DEMANDES et GEO_TYPE_DEMANDES
ALTER TABLE GEO_DEMANDES
ADD CONSTRAINT fk_demande_type_demande
FOREIGN KEY (id_type_demande)
REFERENCES GEO_TYPE_DEMANDES(id_type_demande);


-- -----------------------------------------------------------------------------
--             Insertion de données
-- -----------------------------------------------------------------------------


-- Valeurs par défaut dans la table GEO_ROLES

INSERT INTO GEO_ROLES (name, description) VALUES
('Administrateur','Administrateur système'),
('Collectivite','Collectivité (éditeur)'),
('Association','Association (éditeur)'),
('Club','Club (éditeur)'),
('Utilisateur','Utilisateur simple');

-- Valeurs par défaut dans la table GEO_PERMISSIONS

INSERT INTO GEO_PERMISSIONS (name, description) VALUES
('view_account','Voir sa page compte'),
('view_all_account','Voir toutes les pages comptes'),
('edit_account','Modifier son compte'),
('edit_all_account','Modifier tout les comptes'),
('edit_equipement','Modifier ses équipements'),
('edit_all_equipement','Modifier tout les équipements'),
('create_account','Créer un compte'),
('delete_account','Supprimer un compte'),
('grant_permission','Donner une permission'),
('accept_deny_request','Accepter ou refuser une demande'),
('accept_deny_all_request','Accepter ou refuser toutes les demandes'),
('access_dashboard','Acceder au dashboard');
('view_all_stats','Voir toutes les statistiques sur le dashboard');

-- Ajout des permissions des administrateurs
INSERT INTO GEO_ROLE_PERMISSIONS (role_id, permission_id)
SELECT r.role_id, p.permission_id FROM GEO_ROLES r CROSS JOIN GEO_PERMISSIONS p WHERE r.name='Administrateur';

-- Ajout des des permissions des utilisateurs
INSERT INTO GEO_ROLE_PERMISSIONS (role_id, permission_id) VALUES ((select role_id from GEO_ROLES where lower(name) = 'utilisateur'), (select permission_id from GEO_PERMISSIONS where lower(name) = 'view_account'));
INSERT INTO GEO_ROLE_PERMISSIONS (role_id, permission_id) VALUES ((select role_id from GEO_ROLES where lower(name) = 'utilisateur'), (select permission_id from GEO_PERMISSIONS where lower(name) = 'edit_account'));

-- Heritage des permissions des utilisateurs aux collectivités
INSERT INTO GEO_ROLE_PERMISSIONS (role_id, permission_id)
select (select role_id from GEO_ROLES where lower(name) = 'collectivite'), p.permission_id from GEO_PERMISSIONS p join GEO_ROLE_PERMISSIONS rp using(permission_id) join GEO_ROLES r using(role_id) where lower(r.name) = 'utilisateur';

INSERT INTO GEO_ROLE_PERMISSIONS (role_id, permission_id) VALUES ((select role_id from GEO_ROLES where lower(name) = 'collectivite'), (select permission_id from GEO_PERMISSIONS where lower(name) = 'edit_equipement'));
INSERT INTO GEO_ROLE_PERMISSIONS (role_id, permission_id) VALUES ((select role_id from GEO_ROLES where lower(name) = 'collectivite'), (select permission_id from GEO_PERMISSIONS where lower(name) = 'access_dashboard')); 

-- Heritage des permissions des utilisateurs aux associations
INSERT INTO GEO_ROLE_PERMISSIONS (role_id, permission_id)
select (select role_id from GEO_ROLES where lower(name) = 'association'), p.permission_id from GEO_PERMISSIONS p join GEO_ROLE_PERMISSIONS rp using(permission_id) join GEO_ROLES r using(role_id) where lower(r.name) = 'utilisateur';

INSERT INTO GEO_ROLE_PERMISSIONS (role_id, permission_id) VALUES ((select role_id from GEO_ROLES where lower(name) = 'association'), (select permission_id from GEO_PERMISSIONS where lower(name) = 'edit_equipement'));
INSERT INTO GEO_ROLE_PERMISSIONS (role_id, permission_id) VALUES ((select role_id from GEO_ROLES where lower(name) = 'association'), (select permission_id from GEO_PERMISSIONS where lower(name) = 'access_dashboard')); 

-- Insertion de type de demandes de bases
INSERT INTO GEO_TYPE_DEMANDES (nom, description, alias) VALUES ('Privilège association', 'Demande de creation d un compte de type association', 'request_association');
INSERT INTO GEO_TYPE_DEMANDES (nom, description, alias) VALUES ('Privilège collectivite', 'Demande de creation d un compte de type collectivite', 'request_collectivite');
INSERT INTO GEO_TYPE_DEMANDES (nom, description, alias) VALUES ('Privilège club', 'Demande de creation d un compte de type club', 'request_club');
