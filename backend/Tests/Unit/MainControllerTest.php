<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Controllers\MainController;
use App\Services\Database;

class MainControllerTest extends TestCase
{
    private $mainController;
    protected $db;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mainController = new MainController();
        $this->db = Database::getConnection();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->mainController->resetToDefault();
    }

    protected function truncateCoinStoringTable(): void
    {
        $this->db->query("TRUNCATE TABLE coin_storing");
    }

    public function testSelectProduct_InsufficientMoney()
    {
        $result = $this->mainController->selectProduct(1, 10); // product ID 1 costs more than $50
        $this->assertEquals('Not enough money to buy the product', $result);
    }

    public function testSelectProduct_ProductNotFound()
    {
        $result = $this->mainController->selectProduct(1000, 100); // product ID 1000 does not exist
        $this->assertEquals('Product not found', $result);
    }

    public function testSelectProduct_ExactChangeProvided()
    {
        $result = $this->mainController->selectProduct(1, 15); // product ID 1 costs 15 and user pays exact amount
        $expected = 'Product purchased successfully. Change provided: []'; // Expected change is empty
        $this->assertEquals($expected, $result);
    }
}
