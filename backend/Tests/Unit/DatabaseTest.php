<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\Database;

class DatabaseTest extends TestCase
{
    public function testConnection()
    {
        $connection = Database::getConnection();
        $this->assertNotNull($connection);
    }

}
