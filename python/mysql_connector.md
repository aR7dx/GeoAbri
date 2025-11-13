## Installation du module python "mysql.connector"

Si vous souhaitez lancer le script python "genere_insert_equipement.py" il vous faut obligatoirement installer le module python : mysql.connector. Ce module sert a traiter des requêtes SQL vers une base de données directement depuis le script python. Ici, il nous sert à traiter plus de 365 000 enregistrements.

Avant de procéder à l'installation ouvrez un terminal dans le répértoire:
* **python/**

Pour installer ce module sur Linux (Ubuntu/Mint):
```
sudo apt update
sudo apt install python3-pip
```

_  

Ensuite pour installer le module il vas d'abord falloir créér et initialiser un environnement virtuel (venv) car Linux refuse d'installer un module globalement sur le système par défaut. On vas donc grâce à ce **venv** l'installer uniquement là où on en a besoin (le module).

***Linux (Ubuntu/Mint)***:
```
python3 -m venv venv
source venv/bin/activate 
```
***Windows:***
```
py -m venv venv
.\venv\Scripts\Activate.ps1
```
_
   
Ensuite on install le module:
```
pip install mysql-connector-python
```

Tant que le **venv** est activé, "mysql.connector" sera disponible. Pour le désativer il suffit simplement d'écrire :
```
deactivate
```

## Paramètres de connexion

Au debut du script il y a dictionnaire avec les paramètres de connexion, modifiez les pour vous connecter à votre base de données
Modifier aussi les informations de connexion dans le fichier **components/header.php**.

#### Pour le serveur de développement:

Pour trouver les identifiants de la base de données du serveur de développement il faut d'abord se connecter en sftp au serveur, puis allez dans le dossier **private/Protected** puis ouvrir le fichier **mysql.txt**. 