
# Vending Machine Project

This project combines a frontend React-based vending machine interface and a PHP-based backend API to manage various vending machine operations. It includes handling coin storing, product management, transactions, and utilizes a MySQL database for data storage.

### How to run it
To run this project using Docker, follow these steps:

- Make sure you have Docker installed on your machine.
- Open your terminal or command prompt.
- Run the following command to start the project: `docker-compose up`
- Once the containers are up and running, you can access the frontend at localhost:3000 in your web browser.

This setup utilizes Docker to manage and run both the frontend and backend components of the project within containers, ensuring easy deployment and management of the entire application environment.

## Bluevend-backend

### Overview
The backend handles operations related to coin storing, product management, and transactions via API endpoints.

### Main Functions

- **Insert Coins/Banknotes**
  - Endpoint: `/insertCoins`
  - Allows users to insert coins or banknotes into the system.
  
- **Reset to Default Data**
  - Endpoint: `/resetToDefault`
  - Resets the coin storing data to default values.
  
- **Select a Product**
  - Endpoint: `/selectProduct/{productId}/{userMoney}`
  - Used for purchasing a product by providing the product ID and user's money. Calculates change and deducts the required coins/banknotes from the machine's inventory.

- **Health Check**
  - Endpoint: `/healthCheck`
  - Checks the health status of the server.

### How to Use Bluevend-backend (local)

- Ensure PHP is installed.
- Clone the repository.
- Run `composer install` to install dependencies.
- Set up a MySQL database and configure the connection in `Database.php`.
- Run the application using `php -S localhost:8000` or configure with a web server.
- Interact with the system using API endpoints.

### Execute Unit Testing

- Run `composer install` to install dependencies.
- Execute `./vendor/bin/phpunit tests` via bash.

## React Vending Machine (Frontend)

### Overview

The frontend implements a simple vending machine interface using React, allowing users to interact with the vending machine, select items, insert coins, make purchases, and manage available changes.

### Prerequisites

Ensure Node.js and npm (Node Package Manager) are installed on your machine.

### Running the Application

Run the development server:

```
yarn start
```

Run the unit test:

```
yarn test
```

Access the application at localhost:3000 in your web browser once the development server starts.

### Features
- Item Selection: View available items with prices and remaining stock. Select items for purchase.
- Coin Insertion: Insert various denominations of coins (1฿, 5฿, 10฿, 20฿, 100฿, 500฿, 1000฿) to make a purchase.
- Purchase Transactions: Complete purchases and receive change if applicable.
- Restocking: Restock available products and the machine's change supply.
- Responsive Design: Designed to work on various screen sizes.
### Components
The main components of this project include:

- VendingMachine: Manages the vending machine interface.
- ItemSelection: Displays available items for selection.
- CoinInsertion: Handles coin insertion and available change management.
### Dependencies
- React
- React Toastify: Used for displaying toast notifications.
- Axios: For making HTTP requests to API endpoints.
### APIs
The frontend utilizes API services from Bluevend-backend to manage vending machine operations.