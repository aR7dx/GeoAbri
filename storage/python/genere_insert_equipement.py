import json
import mysql.connector # meme si il est souligné si le script ne renvoie pas d'erreur ce n'est pas grave
import sys
import re


# --- CONFIGURATION ---
DB_CONFIG = {
    "host": "localhost",
    "user": "root",
    "password": "",
    "database": "geoabri",
}
TABLE_NAME = "GEO_EQUIPEMENT"
SOURCE_FILE = "data-es-equipement.json" # necessaire d'avoir ce fichier dans le meme dossier que ce script
ERR_LOG_FILE = "insert_equipement_err" # fichier contenant la liste qui a fait planté le script
REQUEST_BUFFER = 1000 # nb qu'on stocke avant d'envoyer à la db
auto = True # mode de sortie des requetes, true = directement dans la db et false = dans un fichier txt


# --- CONNEXION À LA BASE ---
print("Connexion à la base de données en cours...")
try:
    conn = mysql.connector.connect(**DB_CONFIG)
    cursor = conn.cursor()
except mysql.connector.Error as err:
    print(f"Erreur: impossible de se connecter à la base de données MySQL, {DB_CONFIG['databse']}. \nDétails: {err}")
    sys.exit(1)
print("Connexion à la base de données établie.")


# --- LECTURE DU FICHIER JSON ---
print("Chargement du fichier JSON...")

try:
    with open(SOURCE_FILE, "r", encoding="utf-8") as file:
        equipements = json.load(file)
    print(f"{len(equipements)} équipements chargés.")

except Exception as e:
    print("Erreur de lecture du fichier JSON: {e}")
    cursor.close()
    conn.close()
    sys.exit(1)


"""Convertit les valeurs JSON en chaînes SQL compatibles"""
def normalize(key, value):
    if value is None:
        return "NULL"
    
    invalid_values = ["is_date_homologation_known", "is_date_mise_en_service_known", "is_date_derniers_travaux_known"]
    if (key in invalid_values):
        if (str(value) == "Oui"): 
            return '1'
        if (str(value) == "Non"):
            return '0'
    
    if key == "activites_code":
        return f"'{str(value).replace(';', ',')}'"

    if key == "activites_json":
        if isinstance(value, str):
            # Si la valeur est une chaine, on essaye de corriger tout les defauts du fichier json
            try:
                n_value = str(value).replace(';', '","')
                n_value = str(n_value).replace(': }', ': "NULL"}')
                n_value = str(n_value).replace(', "', '"], "')
                n_value = str(n_value).replace('": "], "', '": "NULL", "')
                n_value = re.sub(r': ([A-Z])', r': ["\1', n_value)
                n_value = str(n_value).replace(' / ', ', ')
                n_value = str(n_value).replace('\n', ' ')

                return f"'{n_value.replace('\'', '\\\'')}'"

            except json.JSONDecodeError:
                # Si le JSON est mal formé, on renvoie en string
                return f"'{value}'"
        else:
            return f"'{json.dumps(value)}'"

    # Convertir les listes en chaîne simple
    if isinstance(value, list):
        txt = ", ".join(map(str, value))
        return f"'{txt.replace('\'', '\\\'')}'"

    # Convertir les dictionnaires (ex: coordonnees)
    if isinstance(value, dict):
        lat = value.get("lat")
        lon = value.get("lon")
        return f"'{lat}, {lon}'" if lat and lon else "NULL"

    # Valeurs numériques → pas de quotes
    if isinstance(value, (int, float)):
        return str(value)

    # Chaînes normales
    n_value = str(value).replace('\n', ' ')
    return f"'{str(n_value).replace('\'', '\\\'')}'"


def build_insert_request(data, table_name="GEO_EQUIPEMENT"):
    """Construit une requête SQL INSERT à partir du JSON."""
    mapping = {
        "famille": "type_famille",
        "code": "type_code"
    }

    for old, new in mapping.items():
        if old in data:
            v = data.pop(old)
            data[new] = v

    # on enleve et remet ces valeurs pour quelles retrouvent leur place d'origine dans la liste
    data['rnb_id'] = data.pop('rnb_id')
    data['commune'] = data.pop('commune')

    columns = ", ".join(data.keys())
    values = ", ".join(normalize(k, v) for k, v in data.items())

    return f"INSERT INTO {table_name} ({columns}) VALUES ({values});"


print("Insertion en cours..")
requests = []
count = 1
duplicate_equipements_count = 0

if (not auto): 
    with open("test.txt", "w", encoding="utf-8") as out:
        for e in equipements[0:2500]: # modifier l'intervalle en fonction de vos besoins
            sql = build_insert_request(e)
            out.write("/*NB: " + str(count) + ",*/ " + sql + "\n")
            count += 1


if (auto):
    # --- TRUNCATE de la table pour effacer les ancinnes données---
    print(f"Suppression de contenu de la table {TABLE_NAME}")
    cursor.execute("SET FOREIGN_KEY_CHECKS = 0;")
    cursor.execute(f"TRUNCATE TABLE {TABLE_NAME};")
    cursor.execute("SET FOREIGN_KEY_CHECKS = 1;")
    conn.commit()
    print("Suppresion terminée.")
    
    for e in equipements:
        try:
            requests.append(build_insert_request(e))
            if (len(requests) == REQUEST_BUFFER):
                for req in requests:
                    try:
                        cursor.execute(req)
                    except mysql.connector.Error as err:
                        if ("1062 (23000): Duplicate entry" in str(err)):
                            duplicate_equipements_count += 1
                            count += 1
                            continue
                        else:
                            print(f"Erreur MySQL sur la requête n°{count} : {err}")
                    count += 1
                conn.commit()
                print(f"{count} lignes insérées...")
                requests = []
        except mysql.connector.Error as err:
            print(f"Erreur en lien avec mysql.connector: {err}")
            with open(ERR_LOG_FILE, "w", encoding="utf-8") as out:
                out.write("Erreur: " + str(err) + "\n\n\n" + "Requete numéro : " + str(count) + "\n\n\n" + str(req))
        except Exception as ex:
            print(f"Erreur inatendue : {ex}")

print("\n\n\n=====RECAP=====")
print(f"{count} équipements traités.")
print(f"Il y avait {duplicate_equipements_count} élements doublons dans le fichier {SOURCE_FILE}")
print("\n\n\n===============")
cursor.close()
conn.close()
print(f"Connexion MySQL fermée.")