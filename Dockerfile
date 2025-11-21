# --------------------
# Build composer
# --------------------
FROM composer:2 AS build

WORKDIR /app

# Copier uniquement les fichiers nécessaires pour installer les dependances
COPY composer.json composer.lock ./

RUN composer install --optimize-autoloader

# --------------------
# Apache
# --------------------
FROM php:8.2-apache

# Installer les extensions PHP nécessaires (pdo_mysql, etc...)
RUN docker-php-ext-install pdo pdo_mysql mbstring

# Activer le module rewrite d'Apache
RUN a2enmod rewrite

# Copier le code de l'application
COPY . .

# Copier le vendor installé dans l'etape composer
COPY --from=build /app/vendor /var/www/html/vendor

# Permissions
RUN chown -R www-data:www-data /var/www/html

# Exposer le port web
EXPOSE 80

# Commande de démarrage de Apache
CMD ["apache2-foreground"]