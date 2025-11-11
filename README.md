<a id="readme-top"></a>


# GeoAbri

![screenshot](./media/screenshot.png "Page d'accueil")

GeoAbri est une application web permettant de localiser rapidement les refuges à proximité en cas de conditions climatiques extrêmes, telles que les tempêtes. Grâce à une interface simple et intuitive, l'utilisateur peut géolocaliser les points d'abri les plus proches de lui en quelques clics.

<p align="right">(<a href="#readme-top">retourner en haut</a>)</p>


# Contributeurs

<a href="https://github.com/aR7dx/GeoAbri/graphs/contributors">
  <img src="https://contrib.rocks/image?repo=aR7dx/GeoAbri" alt="contrib.rocks image" />
</a>

<p align="right">(<a href="#readme-top">retourner en haut</a>)</p>

# Configuration: 
## Installation du module python "mysql.connector" (Optionnel)

Ce module n'est indispensable que si vous chercher à remplir la base de données avec les données du fichier source **data-es-equipement.json**.

Si vous rechercher des informations concernant le module "**mysql.connector**", merci de consulter le fichier [**mysql_connector.md**](./storage/python/mysql_connector.md).

## Définition du point d'entrée du site (Optionnel)

Le fichier **.htaccess** contient déjà certaines règles qui permettent de changer le point d'entrée cela suffit pour le serveur de développement mais en local il faut quand même modifier un autre ficher où l'on doit spécifier d'autoriser à prendre en compte notre fichier **.htaccess**.

Pour ce faire il faut modifier le fichier **/etc/apache2/sites-available/000-default.conf**
```
sudo nano /etc/apache2/sites-available/000-default.conf
```

Il faut obtenir une structure comme celle ci-dessous dans le fichier mentionné, souvent il suffit de rajouter le bloc **Directory**:
```
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

Ensuite entrez ces commandes pour recharger apache avec la nouvelle configuration:
```
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