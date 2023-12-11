<?php
// MainController.php
namespace App\Controllers;

use App\Models\CoinStoring;
use App\Models\Product;

class MainController
{
    private $coinStoringModel;
    private $productModel;

    public function __construct()
    {
        $this->coinStoringModel = new CoinStoring(); // Instantiate the CoinStoring model
        $this->productModel = new Product(); // Instantiate the Product model
    }

    // Insert coins/banknotes
    public function insertCoins()
    {
        // Get the JSON data from the request body
        $json_data = file_get_contents('php://input');

        // Decode the JSON data into a PHP associative array
        $data = json_decode($json_data, true);

        // Check if 'type' is present in the JSON data
        if (isset($data['type'])) {
            $owner = 'user';
            $type = $data['type'];
            $acceptableTypes = [1, 5, 10, 20, 100, 500, 1000];

            // Check if the provided type is acceptable
            if (!in_array($type, $acceptableTypes)) {
                $response = [
                    'error' => "Invalid coin type. Accepted types: " . implode(', ', $acceptableTypes) . " got:" . $type
                ];
                http_response_code(400); // Set appropriate HTTP response code
                echo json_encode($response);
                return; // Stop further execution
            }

            // Process the valid 'type'
            $response["desc"] = $this->coinStoringModel->coinsIncrementByOwnerAndType($owner, $type);
            echo json_encode($response);
        } else {
            // 'type' key is not present in the JSON data
            $response = [
                'error' => "Please provide a 'type' value."
            ];
            http_response_code(400); // Set appropriate HTTP response code
            echo json_encode($response);
        }
    }


    // Reset to default data
    public function resetToDefault()
    {
        $this->coinStoringModel->resetToDefault();
        $this->productModel->resetToDefault();
    }

    // Reset UserCoinStoring
    public function resetUserCoins()
    {
        $this->coinStoringModel->resetUserCoins();
    }

    // Select a product and perform necessary calculations
    public function selectProduct()
    {
        // Get the JSON data from the request body
        $json_data = file_get_contents('php://input');

        // Decode the JSON data into a PHP associative array
        $data = json_decode($json_data, true);
        // Check if 'type' is present in the JSON data
        if (isset($data['id'])) {
            $productId = $data['id'];
            $product = $this->productModel->getProductById($productId); // Get product details

            if ($product === "Product not found") {
                $response = [
                    'error' => "Product not found"
                ];
                http_response_code(400); // Set appropriate HTTP response code
                echo json_encode($response);
                return;
            }

            if ($product['amount'] === 0) {
                $response = [
                    'error' => "Product out of stock"
                ];
                http_response_code(400); // Set appropriate HTTP response code
                echo json_encode($response);
                return;
            }

            $userMoney = $this->coinStoringModel->getUserMoney();

            $productPrice = $product['price'];
            $userChange = $userMoney - $productPrice; // Calculate change

            if ($userChange < 0) {
                $response = [
                    'error' => "Not enough money"
                ];
                http_response_code(400); // Set appropriate HTTP response code
                echo json_encode($response);
                return;
            }

            $changeDetails = $this->calculateChange($userChange); // Calculate the change details

            if ($changeDetails === "Unable to provide exact change") {
                $response = [
                    'error' => "Unable to provide exact change"
                ];
                http_response_code(400); // Set appropriate HTTP response code
                echo json_encode($response);
                return;
            }

            // Update product stock
            $this->productModel->updateProductStock($productId);

            // Deduct coins/banknotes for change
            $this->coinStoringModel->deductCoinsForChange($changeDetails);
            // remove all user coin in db for change
            $this->coinStoringModel->resetUserCoins();

            header('Content-Type: application/json');
            $response["data"] = $changeDetails;
            $response["desc"] = "Product purchased successfully. Change provided:";
            echo json_encode($response);
            return $changeDetails;
        } else {
            // 'itemId' key is not present in the JSON data
            $response = [
                'error' => "Please provide a 'id' value."
            ];
            http_response_code(400); // Set appropriate HTTP response code
            echo json_encode($response);
            return;
        }


    }

    public function getUserMoney()
    {
        $userMoney = $this->coinStoringModel->getUserMoney();
        header('Content-Type: application/json');
        echo json_encode($userMoney);
    }

    public function getAllProducts()
    {
        $products = $this->productModel->getAllProducts();
        header('Content-Type: application/json');
        $response["data"] = $products;
        echo json_encode($response);
    }

    private function calculateChange($change)
    {
        $coins = [1000, 500, 100, 50, 20, 10, 5, 1]; // Denominations of coins/banknotes
        $changeDetails = [];

        // Fetch all coin entries from the database
        $allCoinEntries = $this->coinStoringModel->getAllCoinEntries();

        // Filter only the entries owned by the machine
        $machineCoins = array_filter($allCoinEntries, function ($entry) {
            return $entry['owner'] === 'machine';
        });

        foreach ($coins as $coin) {
            $count = (int) ($change / $coin);

            // Check if the machine has enough of this coin/banknote to provide change
            $availableCount = 0;
            foreach ($machineCoins as $entry) {
                if ($entry['type'] == $coin) {
                    $availableCount += $entry['amount'];
                }
            }

            if ($count > $availableCount) {
                $count = $availableCount; // Adjust count to the available coins/banknotes
            }

            if ($count > 0) {
                $changeDetails[$coin] = $count;
                $change -= $count * $coin;
            }
        }

        if ($change !== 0) {
            return "Unable to provide exact change";
        }

        return $changeDetails;
    }


    public function healthCheck()
    {
        echo "Server is healthy";
    }
}
