<?php
use PHPUnit\Framework\TestCase;

require_once 'class/classRadio.php';

class DatabaseMock {
    public static $pdo;

    public static function connect() {
        return self::$pdo;
    }
}

class RadioMock extends Radio {
    public function __construct() {
        $this->conn = DatabaseMock::connect();
    }
}

class RadioTest extends TestCase {
    private $pdo;
    private $stmt;
    private $radio;

    protected function setUp(): void {
        $this->pdo = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);

        DatabaseMock::$pdo = $this->pdo;
        $this->radio = new RadioMock();
    }

    // 1. Untuk test tambah data radio
    public function testTambahRadio() {
        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->stmt->expects($this->once())->method('execute')->willReturn(true);

        $result = $this->radio->tambahRadio("Motorola DP338");
        $this->assertTrue($result);
    }

    // 2. Untuk test hapus data radio
    public function testHapusRadio() {
        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->stmt->expects($this->once())->method('execute')->willReturn(true);

        $result = $this->radio->hapusRadio(1);
        $this->assertTrue($result);
    }

    // 3. Untuk test update data radio
    public function testUpdateRadio() {
        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->stmt->expects($this->once())->method('execute')->willReturn(true);

        $result = $this->radio->updateRadio(1, "Hytera PT568");
        $this->assertTrue($result);
    }

    // 4. Untuk test menampilkan data radio
    public function testGetAllDataradio() {
        $expected = [
            ['id_radio' => 1, 'tipe_radio' => 'Hytera PT568']
        ];

        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->stmt->method('execute');
        $this->stmt->method('fetchAll')->willReturn($expected);

        $result = $this->radio->getAllDataradio();
        $this->assertEquals($expected, $result);
    }

    // 5. Untuk test mengecek atau validasi data radio
    public function testCekRadioTrue() {
        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->stmt->method('execute');
        $this->stmt->method('fetchColumn')->willReturn(1);

        $result = $this->radio->cekRadio("Hytera PT568");
        $this->assertTrue($result);
    }

    public function testCekRadioFalse() {
        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->stmt->method('execute');
        $this->stmt->method('fetchColumn')->willReturn(0);

        $result = $this->radio->cekRadio("HYT TC700");
        $this->assertFalse($result);
    }
}
