# Bluevend-backend
## Overview

This project is designed to handle various operations related to coin storing, product management, and transactions via an API interface.

## Main Functions

### Insert Coins/Banknotes

Endpoint: `/insertCoins`

This function allows users to insert coins or banknotes into the system. It requires a `type` and `amount` to be provided via a POST request. The inserted coins or banknotes will be associated with the 'user' owner.

### Reset to Default Data

Endpoint: `/resetToDefault`

This function resets the coin storing data to default values. It's typically used for testing or initializing the system with default data.

### Select a Product

Endpoint: `/selectProduct/{productId}/{userMoney}`

This function is used for purchasing a product by providing the product ID and the amount of money the user is inserting. It calculates the change and deducts the required coins/banknotes from the machine's inventory to provide exact change to the user.

### Health Check

Endpoint: `/healthCheck`

This function checks the health status of the server.

## How to Use

- Make sure you have PHP installed.
- Clone this repository.
- Run `composer install` to install dependencies.
- Set up a MySQL database and configure the connection in `Database.php`.
- Run the application using `php -S localhost:8000` or configure with a web server.
- Use API endpoints to interact with the system.

## To Execute Unit Testing
- Run `composer install` to install dependencies.
- execute `./vendor/bin/phpunit tests` via bash

