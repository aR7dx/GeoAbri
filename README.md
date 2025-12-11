<a id="readme-top"></a>


# GeoAbri

GeoAbri est une application web permettant de localiser rapidement les refuges à proximité en cas de conditions climatiques extrêmes, telles que les tempêtes. Grâce à une interface simple et intuitive, l'utilisateur peut géolocaliser les points d'abri les plus proches de lui en quelques clics.

</br>

![screenshot](./public/media/screenshot.png "Page d'accueil")

![screenshot](./public/media/map_screenshot.png "Carte Interactive")

![screenshot](./public/media/alert_screenshot.png "Alerte")

<p align="right">(<a href="#readme-top">retourner en haut</a>)</p>

## 🚀 Déploiement

L'application est déployée sur Render grâce à un conteneur Docker.

<p align="right">(<a href="#readme-top">retourner en haut</a>)</p>

## 🗺️ Carte Interactive

La carte interactive permet de visualiser des milliers d'équipements sportifs sur toute la France avec des performances optimales.

#### 🛠️ Technos

- **Leaflet.js** : Bibliothèque JavaScript pour cartes interactives OpenStreetMap
- **Leaflet.markercluster** : Regroupement automatique des marqueurs proches
- **Nominatim API** : Géocodage et recherche de lieux
- **Wikimedia Commons API** : Récupération d'images des équipements

#### ⚡ Optimisations et performances

- **📊 Échantillonnage Spatial** :  Division en grille (~5.5 km par cellule) avec une limite de 50 équipements par cellule
- **🎯 Limite** : Maximum 3000 équipements affichés simultanément
- **💾 Cache** : Mémorisation des 10 dernières requêtes + cache HTTP 60s
- **📦 Chunked Loading** : Ajout par lots de 200 marqueurs via `requestAnimationFrame`
- **🚀 Seuil de déplacement** : Rafraîchissement si mouvement > 30% ou zoom ≥ 1 niveau
- **⏱️ Debouncing** : 300ms d'attente après arrêt du mouvement
- **🛑 AbortController** : Annulation des requêtes en cours
- **⏳ Écran de chargement** : Blocage des interactions pendant le chargement

#### ✨ Fonctionnalités

- 🔍 Filtres avancés (catégorie, PMR, état, accès, commune)
- 📍 Géolocalisation automatique
- 🏙️ Affichage des zones géographiques des villes
- 💬 Autocomplétion de recherche
- 📋 Fiches détaillées avec itinéraire et images

<p align="right">(<a href="#readme-top">retourner en haut</a>)</p>

# 👥 Contributeurs

<a href="https://github.com/aR7dx/GeoAbri/graphs/contributors">
  <img src="https://contrib.rocks/image?repo=aR7dx/GeoAbri" alt="contrib.rocks image" />
</a>

<p align="right">(<a href="#readme-top">retourner en haut</a>)</p>

# ⚙️ Configuration 
## 📦 Initialisation de Composer (Obligatoire)
Il est indispensable d'initialiser **Composer** pour utiliser l'application. Composer nous sert à générer un fichier **autoload.php** qui va associer les namespaces avec les classes et faire les correspondances dans notre architecture MVC. Composer est également un gestionnaire de bibliothèques. Dans ce projet, nous utilisons la bibliothèque **AltoRouter** qui nous sert à créer notre routeur.  

Pour installer les bibliothèques et initialiser **Composer** afin de générer le fichier **autoload.php**, il faut exécuter la commande suivante :  
```sh
# composer config -g disable-tls true
composer install --optimize-autoloader
```

## 🐍 Installation du module Python "mysql.connector" (Optionnel)

Ce module n'est indispensable que si vous cherchez à remplir la base de données avec les données du fichier source [**data-es-equipement.json**](https://equipements.sports.gouv.fr/explore/dataset/data-es-equipement/export/).

Si vous recherchez des informations concernant le module "**mysql.connector**", merci de consulter le fichier [**mysql_connector.md**](./python/mysql_connector.md).

## 🔧 Définition du point d'entrée du site (Optionnel)

Le fichier **.htaccess** contient déjà certaines règles qui permettent de changer le point d'entrée. Cela suffit pour le serveur de développement, mais en local il faut quand même modifier un autre fichier où l'on doit spécifier d'autoriser la prise en compte de notre fichier **.htaccess**.

Pour ce faire, il faut modifier le fichier **/etc/apache2/sites-available/000-default.conf** :
```sh
sudo nano /etc/apache2/sites-available/000-default.conf
```

Il faut obtenir une structure comme celle ci-dessous dans le fichier mentionné. Souvent, il suffit de rajouter le bloc **Directory** :
```sh
<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/html/

    <Directory /var/www/html/>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

Ensuite, entrez ces commandes pour recharger Apache avec la nouvelle configuration :
```sh
sudo a2enmod rewrite
sudo systemctl restart apache2
```


<!--
## Réalisé avec

* [![PHP][php-icon]][php-url]
* [![MySQL][mysql-icon]][mysql-url]
* [![Javascript][javascript-icon]][javascript-url]
* [![Bootstrap][bootstrap-icon]][bootstrap-url]
-->



<!-- MARKDOWN LINKS & IMAGES -->
[php-icon]:https://img.shields.io/badge/PHP-4f5b93?style=for-the-badge&logo=php&logoColor=FFFFFF
[php-url]:https://www.php.net/
[mysql-icon]:https://img.shields.io/badge/mysql-f29111?style=for-the-badge&logo=mysql&logoColor=00758f
[mysql-url]:https://www.mysql.com/
[javascript-icon]:https://img.shields.io/badge/Javascript-f6e239?style=for-the-badge&logo=javascript&logoColor=000000
[javascript-url]:https://developer.mozilla.org/fr/docs/Learn_web_development/Core/Scripting/What_is_JavaScript
[bootstrap-icon]:https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white
[Bootstrap-url]:https://getbootstrap.com