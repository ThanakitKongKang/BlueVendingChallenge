<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\CoinStoring;
use App\Services\Database;

class CoinStoringTest extends TestCase
{
    private $coinStoring;
    protected $db;

    public function setUp(): void
    {
        parent::setUp();
        $this->coinStoring = new CoinStoring();
        $this->db = Database::getConnection();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->coinStoring->resetToDefault();
    }

    protected function truncateCoinStoringTable(): void
    {
        $this->db->query("TRUNCATE TABLE coin_storing");
    }

    public function testCreateCoinEntry()
    {
        $this->truncateCoinStoringTable();
        // Arrange
        $owner = 'user';
        $type = '1000';
        $amount = 10;

        // Act
        $beforeInsert = $this->coinStoring->getCoinStoringByOwnerAndType($owner, $type);
        $result = $this->coinStoring->createCoinEntry($owner, $type, $amount);
        $afterInsert = $this->coinStoring->getCoinStoringByOwnerAndType($owner, $type);

        // Assert
        $this->assertEquals('New coin entry created successfully', $result);

        // Assert the database state before insertion
        $this->assertNull($beforeInsert);

        // Assert the database state after insertion
        $this->assertNotNull($afterInsert);
        $this->assertEquals($owner, $afterInsert['owner']);
        $this->assertEquals($type, $afterInsert['type']);
        $this->assertEquals($amount, $afterInsert['amount']);
    }



    public function testGetAllCoinEntries()
    {
        $result = $this->coinStoring->getAllCoinEntries();
        $this->assertIsArray($result);
    }

    public function testUpdateCoinAmountByOwnerType()
    {
        // Get the initial entry before the update
        $originalEntry = $this->coinStoring->getCoinStoringByOwnerAndType('user', '1000');

        // Act: Update the coin amount
        $result = $this->coinStoring->updateCoinAmountByOwnerType('user', '1000', 20);

        // Get the updated entry after the update
        $updatedEntry = $this->coinStoring->getCoinStoringByOwnerAndType('user', '1000');

        // Assert: Verify the result and updated values
        $this->assertEquals('Coin amount updated successfully', $result);
        $this->assertNotEquals($originalEntry['amount'], $updatedEntry['amount']);
        $this->assertEquals(20, $updatedEntry['amount']);
    }

    public function testDeductCoinsForChange()
    {
        // Arrange
        $changeDetails = [
            '1' => 1,  // Deduct 5 units of coin '1'
            '5' => 0,  // Deduct 3 units of coin '5'
        ];

        // Act
        $result = $this->coinStoring->deductCoinsForChange($changeDetails);

        // Assert
        $expectedResult = "Coins/banknotes deducted for change successfully";
        $this->assertEquals($expectedResult, $result);

        // Verify that the expected changes are reflected in the database
        $updatedCoin1 = $this->coinStoring->getCoinStoringByOwnerAndType('machine', '1');
        $updatedCoin5 = $this->coinStoring->getCoinStoringByOwnerAndType('machine', '5');

        // Assert that the coin amounts are updated as expected
        $this->assertEquals(0, $updatedCoin1['amount']); // Expected amount after deduction for coin '1'
        $this->assertEquals(1, $updatedCoin5['amount']); // Expected amount after deduction for coin '5'
    }

    public function testResetToDefault()
    {
        $result = $this->coinStoring->resetToDefault();
        $this->assertEquals('Reset to default data successful', $result);
    }
}
