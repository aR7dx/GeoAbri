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


CREATE TABLE GEO_EQUIPEMENT 
(
	installation_numero VARCHAR(10),
	installation_id VARCHAR(4), --  souvent vide
 	creation_dt DATE, --  2025-03-31
	maj_date DATE, --  2025-03-31
 	maj_lien VARCHAR(175),
 	numero VARCHAR(14), --  E024I212310064
 	nom VARCHAR(100), --  Courts de tennis couverts 5
 	type VARCHAR(100), --  Court de tennis
 	coordonnees VARCHAR(40), --  47.31406, 5.08352
 	proprietaire_principal_nom VARCHAR(250), --  SYNDICAT INTERDEPARTEMENTAL POUR LA GESTION DES PARCS DE SPORTS DE BOBIGNY ET LA COURNEUVE (SIGPS)
 	proprietaire_principal_type VARCHAR(100), --  Région
 	autres_proprietaires VARCHAR(255),
 	proprietaire_secondaire_type VARCHAR(100), --  Etat
 	gestionnaire_type VARCHAR(100), --  Etablissement Public
 	co_gestionnaire_type VARCHAR(100),
 	gestion_dsp VARCHAR(3), --  Non ou Oui
 	arrete_ouverture VARCHAR(3), --  Oui ou Non
 	erp_type VARCHAR(3),  --  RPE,CTS,X,R
 	erp_cat INT, --  1,2,3,4 ou 5
 	is_date_homologation_known TINYINT(1), --  1 ou 0
 	homologation_date DATE, --  14/06/2006
 	homologation_periode VARCHAR(30), -- 1975-1984
 	is_date_mise_en_service_known TINYINT(1), -- 1 ou 0
 	mise_en_service_date VARCHAR(4), -- 2004
 	mise_en_service_periode VARCHAR(100), --  à partir de 2004
 	is_date_derniers_travaux_known TINYINT(1), -- 1 ou 0
 	derniers_travaux_date DATE, -- 2004
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
 	acces_handi_mobilite VARCHAR(50), -- Aire de jeu
 	acces_handi_sensoriel VARCHAR(50), -- Aire de jeu
 	is_pdesi_pdipr TINYINT(1),
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
 	utilisateurs VARCHAR(100), -- Clubs sportifs, comités, ligues, fédérations
 	acces_libre VARCHAR(3), -- Oui ou Non
 	ouverture_saisonniere VARCHAR(3), -- Oui ou Non
 	activites VARCHAR(50), -- Tennis
 	observations TEXT,
 	coordonnees_y DECIMAL(10,6), -- 45.7535
 	coordonnees_x DECIMAL(10,6), --  -0.647111
 	activites_code VARCHAR(4), -- 7901
 	activites_json JSON, -- avec autre api
 	completion_taux INT, -- 75 (%)
 	equip_nb INT, -- 23
 	equipement_id VARCHAR(7), -- 9e2651b
 	etat VARCHAR(30), -- Validé
 	type_famille VARCHAR(50), -- Court de tennis
 	type_code VARCHAR(3), -- 501
 	rnb_id VARCHAR(50), -- NM2R8T1HJ3BF
 	commune VARCHAR(250) --  Saintes
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Clé primaire pour GEO_EQUIPEMENT
ALTER TABLE GEO_EQUIPEMENT
ADD CONSTRAINT pk_geo_equipement
PRIMARY KEY (installation_numero);

CREATE TABLE GEO_PERSONNE 
(
	id_personne INT,
	id_typepersonne INT,
	nom VARCHAR(50),
	prenom VARCHAR(50),
	telephone VARCHAR(14),
	mail VARCHAR(75),
	num_addresse VARCHAR(10),
	rue_addresse VARCHAR(75),
	ville_addresse VARCHAR(100),
	code_postal VARCHAR(5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Clé primaire pour GEO_PERSONNE
ALTER TABLE GEO_PERSONNE
ADD CONSTRAINT pk_geo_personne
PRIMARY KEY (id_personne);

CREATE TABLE GEO_TYPE_PERSONNE 
(
	id_typepersonne INT,
	lib_typepersonne VARCHAR(50)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Clé primaire pour GEO_TYPE_PERSONNE
ALTER TABLE GEO_TYPE_PERSONNE
ADD CONSTRAINT pk_geo_type_personne
PRIMARY KEY (id_typepersonne);

CREATE TABLE GEO_PLANNING 
(
	id_rdv INT,
	id_personne INT,
	installation_numero VARCHAR(10),
	PLA_date DATE,
	PLA_heure_debut TIME,
	PLA_heure_fin TIME
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Clé primaire pour GEO_PLANNING
ALTER TABLE GEO_PLANNING
ADD CONSTRAINT pk_geo_planning
PRIMARY KEY (id_rdv);

-- Relation entre GEO_PERSONNE et GEO_TYPE_PERSONNE
ALTER TABLE GEO_PERSONNE
ADD CONSTRAINT fk_personne_typepersonne
FOREIGN KEY (id_typepersonne)
REFERENCES GEO_TYPE_PERSONNE(id_typepersonne);

-- Relation entre GEO_PLANNING et GEO_PERSONNE
ALTER TABLE GEO_PLANNING
ADD CONSTRAINT fk_planning_personne
FOREIGN KEY (id_personne)
REFERENCES GEO_PERSONNE(id_personne);

-- Relation entre GEO_PLANNING et GEO_EQUIPEMENT
ALTER TABLE GEO_PLANNING
ADD CONSTRAINT fk_planning_equipement
FOREIGN KEY (installation_numero)
REFERENCES GEO_EQUIPEMENT(installation_numero);