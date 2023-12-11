<?php
namespace App\Services;

class Database
{
    private static $conn;

    public static function getConnection()
    {
        date_default_timezone_set('Asia/Bangkok');
        if (!self::$conn) {
            $host = getenv('DOCKER_ENV') === 'true' ? 'mysql' : 'localhost';
            $username = 'root';
            $password = 'root';
            $database = 'bluevend';

            self::$conn = new \mysqli($host, $username, $password, $database);

            if (self::$conn->connect_error) {
                error_log("Connection failed: " . self::$conn->connect_error);
                die("Connection failed: Check MySQL server status or configuration");
            }
        }

        return self::$conn;
    }

}
?>
