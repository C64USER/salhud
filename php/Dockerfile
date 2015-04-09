FROM php:5.6-apache

ADD app/composer.json /php/composer.json

WORKDIR /php

RUN apt-get update && apt-get install -y git-core zlib1g-dev mysql-client \
    && curl -L http://pecl.php.net/get/memcache-2.2.7.tgz | tar zx \
    && mv memcache-2.2.7 /usr/src/php/ext/memcache-2.2.7 \
    && docker-php-ext-install pdo pdo_mysql zip memcache-2.2.7 \
    && a2enmod rewrite \
    && service apache2 restart \
    && curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && composer install

WORKDIR /var/www/html
