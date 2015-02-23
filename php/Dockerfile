FROM php:5.6-apache

ADD app/composer.phar /usr/local/bin/composer

ADD app/composer.json /php/composer.json
ADD app/generated-classes /php/generated-classes

WORKDIR /php/

RUN apt-get update && apt-get install -y git-core zlib1g-dev \
    && docker-php-ext-install pdo pdo_mysql zip \
    && a2enmod rewrite \
    && service apache2 restart \
    && composer install

WORKDIR /var/www/html
