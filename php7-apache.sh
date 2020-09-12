#!/usr/bin/env bash

#Installing project
composer install

# Source image CMD: runs Apache in the foreground
apache2-foreground

