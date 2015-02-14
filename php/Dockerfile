FROM php:5.6-apache

COPY ./app /var/www/html

WORKDIR /var/www/html

RUN apt-get update
RUN apt-get install -y git-core
RUN php composer.phar install 
RUN a2enmod rewrite
RUN service apache2 restart