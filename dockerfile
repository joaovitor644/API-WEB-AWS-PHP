FROM php:8.1-apache

# Instala a extensão pdo_pgsql e suas dependências
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql &&  a2enmod rewrite

