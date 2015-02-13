FROM eboraas/apache-php

COPY ./templates/000-default /etc/apache2/sites-enabled/
COPY ./templates/default /etc/apache2/sites-available/
COPY ./templates/ports.conf /etc/apache2/

RUN  rm /var/www/index.html

COPY ./app /var/www/

RUN service apache2 restart