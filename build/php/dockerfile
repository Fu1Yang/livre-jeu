# Utilisation de l'image PHP officielle comme point de départ
FROM php:8.2-apache

# Installation des extensions PHP nécessaires
RUN apt-get update 
RUN apt-get install nano -y
RUN a2enmod rewrite
RUN docker-php-ext-install mysqli pdo pdo_mysql

# # Copie des fichiers de l'application dans le conteneur
# COPY . /var/www/html

# # Définition du répertoire de travail
# WORKDIR /var/www/html

# # Exposition du port 80 pour accéder à l'application web
# EXPOSE 80

# # Commande pour exécuter le serveur web (ici, PHP-FPM)
# CMD ["php-fpm"]
