import json


# nécessaire d'avoir le json et un txt nommé insert_equipement.txt dans le même dossier que ce script
# 📘 Charger le fichier JSON
with open("data-es-equipement.json", "r", encoding="utf-8") as f:
    equipements = json.load(f)

def normalize_value(key, value):
    """Convertit les valeurs JSON en chaînes SQL compatibles, sauf activites_json."""
    if value is None:
        return "NULL"

    # ✅ Garder le JSON brut pour la colonne activites_json
    if key == "activites_json":
        if isinstance(value, str):
            try:
                # Vérifie si c'est une chaîne JSON valide
                json.loads(value)
                return f"'{value.replace('\'', '\\\'')}'"
            except json.JSONDecodeError:
                # Si c’est du texte mal formé, on le transforme en JSON string
                return f"'{json.dumps(value)}'"
        else:
            return f"'{json.dumps(value)}'"

    # 🔹 Convertir les listes en chaîne simple
    if isinstance(value, list):
        txt = ", ".join(map(str, value))
        return f"'{txt.replace('\'', '\'\'')}'"

    # 🔹 Convertir les dictionnaires (ex: coordonnees)
    if isinstance(value, dict):
        lat = value.get("lat")
        lon = value.get("lon")
        return f"'{lat}, {lon}'" if lat and lon else "NULL"

    # 🔹 Valeurs numériques → pas de quotes
    if isinstance(value, (int, float)):
        return str(value)

    # 🔹 Chaînes normales
    return f"'{str(value).replace('\'', '\'\'')}'"


def build_insert_statement(data, table_name="GEO_EQUIPEMENT"):
    """Construit une requête SQL INSERT à partir du JSON."""
    mapping = {
        "famille": "type_famille",
        "code": "type_code"
    }

    # Adapter les noms de colonnes si besoin
    for old, new in mapping.items():
        if old in data:
            data[new] = data.pop(old)

    columns = ", ".join(data.keys())
    values = ", ".join(normalize_value(k, v) for k, v in data.items())

    return f"INSERT INTO {table_name} ({columns}) VALUES ({values});"


# 🧾 Écrire les requêtes SQL dans un fichier texte
with open("insert_equipement.txt", "w", encoding="utf-8") as out:
    for e in equipements:
        sql = build_insert_statement(e)
        out.write(sql + "\n")

print("c'est good")
