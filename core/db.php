<?php
class Database {
    private static $host = 'localhost';
    private static $port = '5432';
    private static $dbname = 'isports_club';
    private static $user = 'postgres';
    private static $password = 'UOG0723002';

    private static $conn = null;

    public static function connect() {
        if (self::$conn === null) {
            try {
                $dsn = "pgsql:host=" . self::$host . ";port=" . self::$port . ";dbname=" . self::$dbname;
                self::$conn = new PDO($dsn, self::$user, self::$password);
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }
        return self::$conn;
    }

    public static function disconnect() {
        self::$conn = null;
    }
}
?>
