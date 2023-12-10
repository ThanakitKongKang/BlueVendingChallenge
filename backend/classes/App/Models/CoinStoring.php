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

    // Reset to default data
    public function resetToDefault()
    {
        // Truncate the table to remove existing data
        $truncateQuery = "TRUNCATE TABLE coin_storing";
        $this->conn->query($truncateQuery);

        // Default data
        $defaultData = [
            ['user', '1000', 1],
            ['user', '500', 1],
            ['user', '100', 1],
            ['user', '50', 1],
            ['user', '20', 1],
            ['user', '10', 1],
            ['user', '5', 1],
            ['user', '1', 1],
            ['machine', '1000', 1],
            ['machine', '500', 1],
            ['machine', '100', 1],
            ['machine', '50', 1],
            ['machine', '20', 1],
            ['machine', '10', 1],
            ['machine', '5', 1],
            ['machine', '1', 1],
        ];

        // Current date
        $currentDate = date('Y-m-d H:i:s');

        // Prepare the INSERT query for default data
        $insertQuery = "INSERT INTO coin_storing (owner, type, amount, createdAt, updatedAt) VALUES ";
        foreach ($defaultData as $index => $data) {
            $insertQuery .= "('" . $data[0] . "', '" . $data[1] . "', " . $data[2] . ", '" . $currentDate . "', '" . $currentDate . "')";
            if ($index !== count($defaultData) - 1) {
                $insertQuery .= ", ";
            }
        }

        // Execute the single INSERT query for all default data
        if ($this->conn->query($insertQuery) === TRUE) {
            return "Reset to default data successful";
        } else {
            return "Error inserting default data: " . $this->conn->error;
        }
    }


    // Method to deduct coins/banknotes for change from the inventory
    public function deductCoinsForChange($changeDetails)
    {
        $updateQueries = [];
        $MACHINE = 'machine';

        foreach ($changeDetails as $coin => $count) {
            $updateQueries[] = "UPDATE coin_storing SET amount = CASE WHEN (amount - $count) < 0 THEN 0 ELSE (amount - $count) END WHERE type = $coin and owner = '$MACHINE'";
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

}
