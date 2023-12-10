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
        $owner = 'user';
        $type = $_POST['type'] ?? null;
        $amount = $_POST['amount'] ?? null;

        // Check if 'type' and 'amount' are set in the POST data
        if ($type === null || $amount === null) {
            return "Type and amount must be provided";
        }
        return $this->coinStoringModel->createCoinEntry($owner, $type, $amount);
    }

    // Reset to default data
    public function resetToDefault()
    {
        $this->coinStoringModel->resetToDefault();
        $this->productModel->resetToDefault();
    }

    // Select a product and perform necessary calculations
    public function selectProduct($productId, $userMoney)
    {
        $product = $this->productModel->getProductById($productId); // Get product details

        if ($product === "Product not found") {
            return "Product not found";
        }

        $productPrice = $product['price'];
        $userChange = $userMoney - $productPrice; // Calculate change

        if ($userChange < 0) {
            return "Not enough money to buy the product";
        }

        $changeDetails = $this->calculateChange($userChange); // Calculate the change details

        if ($changeDetails === "Unable to provide exact change") {
            return "Unable to provide exact change";
        }

        // Update product stock
        $this->productModel->updateProductStock($productId);

        // Deduct coins/banknotes for change
        $this->coinStoringModel->deductCoinsForChange($changeDetails);

        return "Product purchased successfully. Change provided: " . json_encode($changeDetails);
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
