<?php
class Database {
    private static $host = "localhost";
    private static $dbname = "db_inrakom";
    private static $username = "root";
    private static $password = "";
    private static $conn;

    public static function connect() {
        if (!isset(self::$conn)) {
            try {
                self::$conn = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$dbname, self::$username, self::$password);
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Koneksi Gagal: " . $e->getMessage());
            }
        }
        return self::$conn;
    }
}
?>