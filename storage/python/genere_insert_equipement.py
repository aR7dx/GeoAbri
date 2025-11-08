import json

# nécessaire d'avoir le json dans le même dossier que ce script


'''Ouverture et lecture du fichier source'''
print("Chargement du fichier JSON...")

with open("data-es-equipement.json", "r", encoding="utf-8") as file:
    equipements = json.load(file)

print("Chargement du fichier JSON terminé.")



"""Convertit les valeurs JSON en chaînes SQL compatibles"""
def normalize_value(key, value):
    if value is None:
        return "NULL"
    
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
                n_value = str(n_value).replace(': A', ': ["A')
                n_value = str(n_value).replace(' / ', ', ')

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
    return f"'{str(value).replace('\'', '\\\'')}'"


def build_insert_statement(data, table_name="GEO_EQUIPEMENT"):
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
    values = ", ".join(normalize_value(k, v) for k, v in data.items())

    return f"INSERT INTO {table_name} ({columns}) VALUES ({values});"


"""Écrire les requêtes SQL dans un fichier texte"""

fichier_sortie = "insert_equipement.txt"

print(f"Ecriture en cours dans le fichier {fichier_sortie}...")

with open(fichier_sortie, "w", encoding="utf-8") as out:
    for e in equipements[0:99]: # limite pour l'instant sinon les 365 000+ élements font crash lorsque l'on veut ouvrir le fichier de sortie
        sql = build_insert_statement(e)
        out.write(sql + "\n")

print(f"Ecriture dans le fichier {fichier_sortie} terminée.")
