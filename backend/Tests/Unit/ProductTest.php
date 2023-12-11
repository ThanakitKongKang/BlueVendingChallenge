<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Product;
use App\Services\Database;

class ProductTest extends TestCase
{
    private $productModel;
    protected $db;

    protected function setUp(): void
    {
        parent::setUp();

        $this->productModel = new Product();
        $this->db = Database::getConnection();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->productModel->resetToDefault();
    }

    protected function truncateProduct(): void
    {
        $this->db->query("TRUNCATE TABLE products");
    }


    public function testCreateProduct()
    {
        $result = $this->productModel->createProduct('Test Product', 5, 10);
        $this->assertEquals('New product created successfully', $result);
    }

    public function testGetProductById()
    {
        $productId = 1;
        $product = $this->productModel->getProductById($productId);
        $this->assertIsArray($product);
    }

    public function testUpdateProduct()
    {
        $productId = 1;
        $result = $this->productModel->updateProduct($productId, 'Updated Product', 8, 20);
        $this->assertEquals('Product updated successfully', $result);
    }

    public function testUpdateProductStock()
    {
        $productId = 1;
        $result = $this->productModel->updateProductStock($productId);
        $this->assertEquals('Product stock updated successfully', $result);
    }

    public function testDeleteProduct()
    {
        $productId = 1;
        $result = $this->productModel->deleteProduct($productId);
        $this->assertEquals('Product deleted successfully', $result);
    }

    public function testGetAllProducts()
    {
        $result = $this->productModel->getAllProducts();
        $this->assertIsArray($result);
    }

    public function testResetToDefault()
    {
        $result = $this->productModel->resetToDefault();
        $this->assertEquals('Reset to default products successful', $result);
    }
}
