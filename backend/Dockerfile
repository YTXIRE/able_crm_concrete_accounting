FROM php:7.4-apache

RUN apt-get update

RUN apt-get install -fyqq \
    bash curl wget rsync ca-certificates openssl ssh git tzdata openntpd \
    libxrender1 fontconfig libc6 \
    mariadb-client gnupg binutils-gold autoconf \
    g++ gcc gnupg libgcc1 linux-headers-amd64 make python

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer \
 && chmod 755 /usr/bin/composer

RUN docker-php-ext-install bcmath pdo_mysql

RUN apt-get install -fyqq libfreetype6 libfreetype6-dev libpng16-16 libpng-dev libjpeg62-turbo libjpeg62-turbo-dev \
 && docker-php-ext-install gd \
 && apt-get remove -fyqq libfreetype6-dev libpng-dev libjpeg62-turbo-dev

RUN apt-get install -fyqq zip libzip-dev \
 && docker-php-ext-install zip \
 && apt-get remove -fyqq libzip-dev

COPY .htaccess /var/www/html
COPY ./. /var/www/html

WORKDIR /var/www/html

RUN chmod 777 /var/www/html

RUN chown www-data:www-data /var/www/html/web
RUN chown www-data:www-data /var/www/html/web/files
RUN chown www-data:www-data /tmp

RUN a2enmod rewrite
RUN a2enmod headers

RUN rm -rf ./config/db.php

ADD ./config/example_db.php ./config/db.php
