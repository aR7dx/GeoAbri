## Installation du module python "mysql.connector"

Si vous souhaitez lancer le script python "genere_insert_equipement.py" il vous faut obligatoirement installer le module python : mysql.connector. Ce module sert a traiter des requêtes SQL vers une base de données directement depuis le script python. Ici, il nous sert à traiter plus de 365 000 enregistrements.

Avant de procéder à l'installation ouvrez un terminal dans le répértoire:
* **storage/python**

Pour installer ce module sur Linux (Ubuntu/Mint):
```
sudo apt update
sudo apt install python3-pip
```

Ensuite pour installer le module il va d'abord falloir créér et initialiser un environnement virtuel (venv) car Linux refuse d'installer un module globalement sur le système. On vas donc grâce à ce (venv) l'installer uniquement la où on en a besoin (le module).

```
python -m venv venv
source venv/bin/activate
pip install mysql-connector-python
```

Tant que le (venv) est activé, "mysql.connector" sera disponible. Pour le désativer il suffit simplement d'écrire :
```
deactivate
```