FROM php:5.6-apache

ADD app/composer.phar /var/www/html/
ADD app/composer.json /var/www/html/

RUN apt-get update && apt-get install -y git-core zlib1g-dev \
    && docker-php-ext-install pdo pdo_mysql zip \
    && php composer.phar install && a2enmod rewrite \
    && service apache2 restart \
    && ln -s vendor/propel/propel/bin/propel.php propel2
