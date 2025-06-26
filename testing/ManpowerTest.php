<?php
use PHPUnit\Framework\TestCase;

require_once 'class/classManpower.php';

class DatabaseMock {
    public static $pdo;

    public static function connect() {
        return self::$pdo;
    }
}

class ManpowerMock extends Manpower {
    public function __construct() {
        $this->conn = DatabaseMock::connect();
    }
}

class ManpowerTest extends TestCase {
    private $pdo;
    private $stmt;
    private $manpower;

    protected function setUp(): void {
        $this->pdo = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);

        DatabaseMock::$pdo = $this->pdo;
        $this->manpower = new ManpowerMock();
    }

    // 1. Untuk test tambah data manpower
    public function testTambahManpower() {
        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->stmt->expects($this->once())->method('execute')->willReturn(true);

        $result = $this->manpower->tambahManpower("Gitarius", "12345678", "IT");
        $this->assertTrue($result);
    }

    // 2. Untuk test hapus data manpower
    public function testHapusManpower() {
        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->stmt->expects($this->once())->method('execute')->willReturn(true);

        $result = $this->manpower->hapusManpower(1);
        $this->assertTrue($result);
    }

    // 3. Untuk test update data manpower
    public function testUpdateManpower() {
        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->stmt->expects($this->once())->method('execute')->willReturn(true);

        $result = $this->manpower->updateManpower(1, "Santoso", "87654321", "HR");
        $this->assertTrue($result);
    }

    // 4. Untuk test mengecek atau validasi data nik
    public function testCekNikTrue() {
        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->stmt->method('execute');
        $this->stmt->method('fetchColumn')->willReturn(1);

        $result = $this->manpower->cekNIK("87654321");
        $this->assertTrue($result);
    }

    public function testCekNikFalse() {
        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->stmt->method('execute');
        $this->stmt->method('fetchColumn')->willReturn(0);

        $result = $this->manpower->cekNIK("12345678");
        $this->assertFalse($result);
    }

    // 5. Untuk test menampilkan data manpower
    public function testGetAllDatamanpower() {
        $expected = [
            ['id_manpower' => 1, 'nama_karyawan' => 'Santoso', 'nik' => '87654321', 'departemen' => 'HR']
        ];

        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->stmt->method('execute');
        $this->stmt->method('fetchAll')->willReturn($expected);

        $result = $this->manpower->getAllDatamanpower();
        $this->assertEquals($expected, $result);
    }
}
