## Installation

Requirements: 
- docker, docker-compose installed
- check the port 8181 is not being used

The installation is very simle, only execute at project root:

    docker-compose up -d
      
This will build a docker image according the:
- docker-compose.yaml
- php7-apache.dockerfile
- php7-apache.sh

It will make an Docker image with:
- Apache 2 server
- PHP 7.4 and required extensions

The respective service serves thought 80 port but is mapped to 8181 port in the host machine, in the way to not use
one common port as 80 that maybe other process is using it (only take care other process in host machine is not using the 8181 port)

The project sources are mapped to host

It will also setup and run the respective container and, in the startup, the
project will be installed automatically

## Test

Execute the PHPUnit tests:

    composer tests

You can see the PHPUnit tests at test/ApiTest.php

## Interactivce CLI:

I made a CLI for easy and interactive usage of the app

Execute the vending machine CLI:  ./vendingmachine.sh

Note: There is not a composer script for this because they can not be interactives (not TTY)

you can also add products to the vending mahcine: http://localhost:8181/api/product/add/SODA/price/1.1

# API Access

Endpoint:
    api/vendingmachine/actions/<ACTIONS LIST SEPARATED BY COMMAS>

i.e.:
    http://localhost:8181/api/vendingmachine/actions/SERVICE-0.25-20, SERVICE-0.10-12, SERVICE-SODA-6, SERVICE-WATER-12, SERVICE-JUICE-7, 1, 0.25, 0.25, GET-SODA

## Scripts

./vendingmachine.sh: execute the vending machine interative CLI

Custom though Docker Composer scripts:
- Clear the production cache: composer cache
- Clear the development cache: composer cache:clear

# Entities

We have 3 entities:

    --------------------------------------                              --------------------
    |   VendingMachine                   |                              |       Money       |
    --------------------------------------                              --------------------
    | id                                 |  (1)              (0..n)     | id               |
    | <collection> availableProducts     |-----------[ HAS ]------------| amount           |
    | <collection> availableMoney        |                              | count            |
    | <collection> insertedMoney         |                              |                  |
    | <string> result                    |                              --------------------
    ---------------------------------------
           |
           |  (1)
           |
        < HAS >
           |
           |  (0..n)
           |
    --------------------------
    |   Product              |
    --------------------------
    | id                     |
    | name                   |
    | price                  |
    | count                  |
    -------------------------


# Implementation:

I've suposed an API microservices oriented architecture, for this I consume the business logic through API instead by services

I've used the Symfony 5 framework

The project architecture is based the Clean Architecture, proposed by Robert Martin.

It consists in 4 layers. The key is that each layer does not know about external layers, but knows about internal layers

Regarding these 4 layers:

- (1) The entities knows only about entities 
- (2) The services and UseCases knows about other services and UseCases and Entities, but nothing else (neither DB implementation, Presenters, Adapters, ...)
- (3) The interface adapters (interfaceAdapters/*) in the same way
- (4) The controllers, ... know all

From more external to inernal layers, we have 4 layers:

- (4) Framework & drivers: DB implementation, external interfaces, UI, web, devices
- (3) Interface Adapters (a.k.a. as Presenter layer): Presenters, Gateways, ...
- (2) Application Business Logic (a.k.a. Use Cases layer): use cases
- (1) Enterprise Business Logic (a.k.a. Entities layer): entities

Also we have the Domain (at 'domain' or Domain namespace ) is not a layer but it envolves:

- Use Cases layer
- Entities layer

