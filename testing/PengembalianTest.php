<?php
use PHPUnit\Framework\TestCase;

require_once 'class/classPengembalian.php';

class DatabaseMock {
    public static $pdo;

    public static function connect() {
        return self::$pdo;
    }
}

class PengembalianMock extends Pengembalian {
    public function __construct() {
        $this->conn = DatabaseMock::connect();
    }
}

class PengembalianTest extends TestCase {
    private $pdo;
    private $stmt;
    private $pengembalian;

    protected function setUp(): void {
        $this->pdo = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);

        DatabaseMock::$pdo = $this->pdo;
        $this->pengembalian = new PengembalianMock();
    }

    // 1. Untuk test ambil data dari tabel radio
    public function testGetRadioList() {
        $expected = [['id_radio' => 1, 'tipe_radio' => 'Hytera PT568']];
        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->stmt->method('execute');
        $this->stmt->method('fetchAll')->willReturn($expected);

        $result = $this->pengembalian->getRadioList();
        $this->assertEquals($expected, $result);
    }

    // 2. Untuk test hapus data pengembalian
    public function testHapusPengembalian() {
        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->stmt->method('execute')->willReturn(true);
        $this->pdo->expects($this->once())->method('beginTransaction');
        $this->pdo->expects($this->once())->method('commit');

        $result = $this->pengembalian->hapusPengembalian(1);
        $this->assertTrue($result);
    }

    // 3. Untuk test update data pengembalian
    public function testUpdatePengembalian() {
        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->stmt->method('execute')->willReturn(true);
        $this->stmt->method('fetchColumn')->willReturn(1);

        $this->pdo->expects($this->once())->method('beginTransaction');
        $this->pdo->expects($this->once())->method('commit');

        $result = $this->pengembalian->updatePengembalian(1, 1, '222ABCD111', 'Radio second', '2025-07-01');
        $this->assertTrue($result);
    }

    // 4. Untuk test menampilkan data pengembalian
    public function testGetAllDatapengembalian() {
        $expected = [['id_peminjaman' => 1, 'nama_karyawan' => 'Santoso']];
        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->stmt->method('execute');
        $this->stmt->method('fetchAll')->willReturn($expected);

        $result = $this->pengembalian->getAllDatapengembalian();
        $this->assertEquals($expected, $result);
    }

    // 5. Untuk test menampilkan data berdasarkan filter tanggal
    public function testGetDataPengembalianByTanggal() {
        $expected = [['id_peminjaman' => 1, 'tanggal_baru' => '2025-07-01']];
        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->stmt->method('execute');
        $this->stmt->method('fetchAll')->willReturn($expected);

        $result = $this->pengembalian->getDataPengembalianByTanggal('2025-06-25', '2025-07-10');
        $this->assertEquals($expected, $result);
    }

    // 6. Untuk test menghitung total data pengembalian
    public function testGetTotalPengembalian() {
        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->stmt->method('execute');
        $this->stmt->method('fetch')->willReturn(['total' => 7]);

        $this->assertEquals(7, $this->pengembalian->getTotalPengembalian());
    }
}
