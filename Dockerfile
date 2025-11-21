FROM php:8.2-apache

# Installer les extensions PHP nécessaires (pdo_mysql, etc...)
RUN docker-php-ext-install pdo pdo_mysql

# Activer le module rewrite d'Apache
RUN a2enmod rewrite

# Copier le code de l'application
COPY . /var/www/html/

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
RUN composer install --optimize-autoloader --no-dev

# Permissions
RUN chown -R www-data:www-data /var/www/html

# Exposer le port web
EXPOSE 80

# Commande de démarrage de Apache
CMD ["apache2-foreground"]