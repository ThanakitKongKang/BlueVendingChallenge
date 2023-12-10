<?php
namespace App\Models;
use App\Services\Database;

class Product
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    // Create a new product
    public function createProduct($name, $amount, $price)
    {
        $currentDate = date('Y-m-d H:i:s');
        $sql = "INSERT INTO products (name, amount, price, createdAt, updatedAt) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('sddss', $name, $amount, $price, $currentDate, $currentDate);

        if ($stmt->execute()) {
            return "New product created successfully";
        } else {
            return "Error: " . $stmt->error;
        }
    }

    // Read a product by ID
    public function getProductById($id)
    {
        $sql = "SELECT * FROM products WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return "Product not found";
        }
    }

    // Update a product
    public function updateProduct($id, $name, $amount, $price)
    {
        $updatedAt = date('Y-m-d H:i:s');
        $sql = "UPDATE products SET name = ?, amount = ?, price = ?, updatedAt = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('sddsi', $name, $amount, $price, $updatedAt, $id);

        if ($stmt->execute()) {
            return "Product updated successfully";
        } else {
            return "Error updating product: " . $stmt->error;
        }
    }

    // Update product stock by decrementing the count of available products
    public function updateProductStock($productId)
    {
        $sql = "UPDATE products SET amount = amount - 1 WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $productId);

        if ($stmt->execute()) {
            return "Product stock updated successfully";
        } else {
            return "Error updating product stock: " . $stmt->error;
        }
    }

    // Delete a product
    public function deleteProduct($id)
    {
        $sql = "DELETE FROM products WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);

        if ($stmt->execute()) {
            return "Product deleted successfully";
        } else {
            return "Error deleting product: " . $stmt->error;
        }
    }

    // Reset products table to default values
    public function resetToDefault()
    {
        // Truncate the table to remove existing data
        $truncateQuery = "TRUNCATE TABLE products";
        $this->conn->query($truncateQuery);

        // Default data
        $defaultData = [
            ['Oishi Chakulza', 6, 15],
            ['Pepsi can', 10, 12],
            ['Snickers chocolate 35g.', 20, 22],
            ['Lays', 6, 40],
        ];

        // Current date
        $currentDate = date('Y-m-d H:i:s');

        // Prepare and execute INSERT statements for default data
        foreach ($defaultData as $data) {
            $insertQuery = "INSERT INTO products (name, amount, price, createdAt, updatedAt) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($insertQuery);
            $stmt->bind_param('sidss', $data[0], $data[1], $data[2], $currentDate, $currentDate);
            $stmt->execute();
        }

        return "Reset to default products successful";
    }
}
