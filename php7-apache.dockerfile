FROM php:7-apache
MAINTAINER adrianheras@gmail.com

# Installing the "composer install" and PHP Code Sniffer dependencies
RUN apt-get update -y \
  && apt-get install -y \
    git \
    libxml2-dev zip unzip

# Installing the PHP dependecies
RUN docker-php-ext-install pdo pdo_mysql mysqli xmlwriter tokenizer simplexml

WORKDIR /var/www/vendingmachine

# Composer and project installation
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Rest at CMD execution script, finishing with source image CMD
CMD ["bash","-c", "./php7-apache.sh"]


