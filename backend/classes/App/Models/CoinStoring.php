<?php
namespace App\Models;

use App\Services\Database;

class CoinStoring
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    // Create new coin entry
    public function createCoinEntry($owner, $type, $amount)
    {
        $currentDate = date('Y-m-d H:i:s');
        $sql = "INSERT INTO coin_storing (owner, type, amount, createdAt, updatedAt) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ssdss', $owner, $type, $amount, $currentDate, $currentDate);

        if ($stmt->execute()) {
            return "New coin entry created successfully";
        } else {
            return "Error: " . $stmt->error;
        }
    }

    // Retrieve coin storing entry by owner and type
    public function getCoinStoringByOwnerAndType($owner, $type)
    {
        // Prepare and execute SQL query to fetch the entry
        $sql = "SELECT * FROM coin_storing WHERE owner = ? AND type = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ss', $owner, $type);
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch the entry details
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null; // Return null if no entry found
        }
    }

    // Get all coin entries
    public function getAllCoinEntries()
    {
        $sql = "SELECT * FROM coin_storing";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            $coinEntries = array();
            while ($row = $result->fetch_assoc()) {
                $coinEntries[] = $row;
            }
            return $coinEntries;
        } else {
            return "No coin entries found";
        }
    }

    // Get all machine coin available
    public function getMachineCoins()
    {
        $MACHINE = 'machine';
        $sql = "SELECT `type`,amount FROM coin_storing WHERE owner = '$MACHINE'";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            $coinEntries = array();
            while ($row = $result->fetch_assoc()) {
                $coinEntries[] = $row;
            }
            return $coinEntries;
        } else {
            return "No coin entries found";
        }
    }

    // Update coin amount by owner, type, and amount
    public function updateCoinAmountByOwnerType($owner, $type, $newAmount)
    {
        $updatedAt = date('Y-m-d H:i:s');
        $sql = "UPDATE coin_storing SET amount = ?, updatedAt = ? WHERE owner = ? AND type = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('dsss', $newAmount, $updatedAt, $owner, $type);

        if ($stmt->execute()) {
            return "Coin amount updated successfully";
        } else {
            return "Error updating coin amount: " . $stmt->error;
        }
    }

    public function resetToDefault()
    {
        $userResetResult = $this->resetUserCoins();
        $machineResetResult = $this->resetMachineCoins();

        if ($userResetResult === true && $machineResetResult === true) {
            return "Reset to default data successful";
        } else {
            return "Error resetting to default data";
        }
    }

    public function resetUserCoins()
    {
        $userDefaultData = [
            ['user', '1000', 0],
            ['user', '500', 0],
            ['user', '100', 0],
            ['user', '50', 0],
            ['user', '20', 0],
            ['user', '10', 0],
            ['user', '5', 0],
            ['user', '1', 0],
        ];

        $currentDate = date('Y-m-d H:i:s');

        return $this->resetOwnerData($userDefaultData, $currentDate);
    }

    public function resetMachineCoins()
    {
        $machineDefaultData = [
            ['machine', '1000', 1],
            ['machine', '500', 2],
            ['machine', '100', 5],
            ['machine', '50', 5],
            ['machine', '20', 10],
            ['machine', '10', 10],
            ['machine', '5', 10],
            ['machine', '1', 10],
        ];

        $currentDate = date('Y-m-d H:i:s');

        return $this->resetOwnerData($machineDefaultData, $currentDate);
    }

    private function resetOwnerData($defaultData, $currentDate)
    {
        $values = [];
        foreach ($defaultData as $data) {
            $values[] = "('" . $data[0] . "', '" . $data[1] . "', " . $data[2] . ", '" . $currentDate . "', '" . $currentDate . "')";
        }

        $insertQuery = "INSERT INTO coin_storing (owner, type, amount, createdAt, updatedAt) VALUES " . implode(', ', $values) . "
                        ON DUPLICATE KEY UPDATE 
                        amount = VALUES(amount), 
                        createdAt = VALUES(createdAt), 
                        updatedAt = VALUES(updatedAt)";

        if ($this->conn->query($insertQuery) === TRUE) {
            return true;
        } else {
            return false;
        }
    }

    public function coinsIncrementByOwnerAndType($owner, $type)
    {
        $existingRecord = $this->getCoinStoringByOwnerAndType($owner, $type);

        if ($existingRecord) {
            // If a record exists, update the amount by incrementing it by 1
            $newAmount = $existingRecord['amount'] + 1;
            return $this->updateCoinAmountByOwnerType($owner, $type, $newAmount);
        } else {
            // If no record exists, create a new record with value = 1
            return $this->createCoinEntry($owner, $type, 1);
        }
    }

    // Method to deduct coins/banknotes for change from the inventory
    public function deductCoinsForChange($changeDetails)
    {
        $updateQueries = [];
        $MACHINE = 'machine';

        foreach ($changeDetails as $coin => $count) {
            $updateQueries[] = "UPDATE coin_storing SET amount = (amount - $count) WHERE type = '$coin' and owner = '$MACHINE'";
        }
        // Execute all update queries as a transaction
        $this->conn->begin_transaction();

        foreach ($updateQueries as $query) {
            if (!$this->conn->query($query)) {
                $this->conn->rollback();
                return "Error updating coin/banknote inventory";
            }
        }

        $this->conn->commit();
        return "Coins/banknotes deducted for change successfully";
    }

    public function getUserMoney()
    {
        // Construct the SQL query using a CASE statement to calculate total money
        $sql = "SELECT SUM((CASE `type`
                        WHEN '1' THEN 1
                        WHEN '5' THEN 5
                        WHEN '10' THEN 10
                        WHEN '20' THEN 20
                        WHEN '100' THEN 100
                        WHEN '500' THEN 500
                        WHEN '1000' THEN 1000
                     END) * amount) AS total_money
                FROM coin_storing
                WHERE owner = 'user' AND `type`";

        $result = $this->conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $totalUserMoney = $row['total_money'] ?? 0;

            return $totalUserMoney;
        }

        return 0;
    }

}
