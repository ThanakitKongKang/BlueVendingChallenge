# React Vending Machine

This project implements a simple vending machine interface using React. It allows users to select items, insert coins, make purchases, restock products, and manage available changes.

### Prerequisites
Make sure you have Node.js and npm (Node Package Manager) installed on your machine.

### Running the Application
Run the development server:
```yarn start```

Run the unit testing:
```yarn test```

Once the development server starts, you can access the application by visiting ```localhost:3000``` in your web browser.

## Features
- Item Selection: View available items with their prices and remaining stock. Select items for purchase.
- Coin Insertion: Insert various denominations of coins (1฿, 5฿, 10฿, 20฿, 100฿, 500฿, 1000฿) to make a purchase.
- Purchase Transactions: Complete purchases and receive change if applicable.
- Restocking: Restock the available products and the machine's change supply.
- Responsive Design: The application is designed to work on various screen sizes.
## Components
The main components of this project include:

- VendingMachine: The main component managing the vending machine interface.
- ItemSelection: Component displaying available items for selection.
- CoinInsertion: Component for inserting coins and managing available changes.
## Dependencies
React
React Toastify: Used for displaying toast notifications.
Axios: For making HTTP requests to the API endpoints.
## APIs
This project utilizes API services for managing vending machine operations like fetching items, making purchases, inserting coins, restocking products, and managing available changes.