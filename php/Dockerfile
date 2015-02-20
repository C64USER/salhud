FROM php:5.6-apache

ADD app /var/www/html/

RUN apt-get update && apt-get install -y git-core zlib1g-dev \
    && docker-php-ext-install pdo pdo_mysql zip \
    && a2enmod rewrite \
    && service apache2 restart \
    && php composer.phar install \
    && ln -s vendor/propel/propel/bin/propel.php propel2
