#!/usr/bin/env bash

# Waiting the MySQL service is serving
./wait-for-it.sh miservicio_mysql:3306 -- echo "MySQL service is up !!"

#Installing project
composer install

#Applying DB migrations
bin/console doctrine:migrations:migrate

# Source image CMD: runs Apache in the foreground
apache2-foreground

