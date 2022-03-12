FROM php:7.2-apache

# Update and Download Packages Debian
RUN apt-get update --yes

# Install Dependencies
RUN apt-get install --yes \
 autoconf \
 bzip2 \
 curl \
 gettext \
 git \
 libbz2-dev \
 libcurl4-openssl-dev \
 libicu-dev \
 libzip-dev \
 libfreetype6-dev \
 libgd-dev \
 libjpeg62-turbo-dev \
 libpq-dev\
 libpng-dev \
 libssl-dev\
 libtidy-dev \
 libxslt-dev \
 libxml2-dev \
 pkg-config \
 zip

COPY --from=composer:2.2.7 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.json
RUN composer install

COPY .env .env