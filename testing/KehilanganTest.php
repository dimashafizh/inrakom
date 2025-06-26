<?php
use PHPUnit\Framework\TestCase;

require_once 'class/classKehilangan.php';

class DatabaseMock {
    public static $pdo;
    public static function connect() {
        return self::$pdo;
    }
}

class KehilanganMock extends Kehilangan {
    public function __construct() {
        $this->conn = DatabaseMock::connect();
    }
}

class KehilanganTest extends TestCase {
    private $pdo;
    private $stmt;
    private $kehilangan;

    protected function setUp(): void {
        $this->pdo = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);

        DatabaseMock::$pdo = $this->pdo;
        $this->kehilangan = new KehilanganMock();
    }

    // 1. Untuk test ambil data dari tabel radio
    public function testGetRadioList() {
        $expected = [['id_radio' => 1, 'tipe_radio' => 'Hytera PT568']];
        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->stmt->method('execute');
        $this->stmt->method('fetchAll')->willReturn($expected);

        $result = $this->kehilangan->getRadioList();
        $this->assertEquals($expected, $result);
    }

    // 2. Untuk test hapus data kehilangan
    public function testHapusKehilangan() {
        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->stmt->method('execute')->willReturn(true);
        $this->pdo->expects($this->once())->method('beginTransaction');
        $this->pdo->expects($this->once())->method('commit');

        $result = $this->kehilangan->hapusKehilangan(1);
        $this->assertTrue($result);
    }

    // 3. Untuk test update data kehilangan
    public function testUpdateKehilangan() {
        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->stmt->method('execute')->willReturn(true);
        $this->stmt->method('fetchColumn')->willReturn(1);
        $this->pdo->expects($this->once())->method('beginTransaction');
        $this->pdo->expects($this->once())->method('commit');

        $result = $this->kehilangan->updateKehilangan(1, 1, '222ABCD111', 'Hilang di lapangan', '2025-07-01');
        $this->assertTrue($result);
    }

    // 4. Untuk test menampilkan data kehilangan
    public function testGetAllDatakehilangan() {
        $expected = [['id_peminjaman' => 1, 'nama_karyawan' => 'Santoso']];
        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->stmt->method('execute');
        $this->stmt->method('fetchAll')->willReturn($expected);

        $result = $this->kehilangan->getAllDatakehilangan();
        $this->assertEquals($expected, $result);
    }

    // 5. Untuk test menampilkan data berdasarkan filter tanggal
    public function testGetDataKehilanganByTanggal() {
        $expected = [['id_peminjaman' => 1, 'tanggal_baru' => '2025-07-01']];
        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->stmt->method('execute');
        $this->stmt->method('fetchAll')->willReturn($expected);

        $result = $this->kehilangan->getDataKehilanganByTanggal('2025-06-25', '2025-07-10');
        $this->assertEquals($expected, $result);
    }

    // 6. Untuk test menghitung total data kehilangan
    public function testGetTotalKehilangan() {
        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->stmt->method('execute');
        $this->stmt->method('fetch')->willReturn(['total' => 5]);

        $this->assertEquals(5, $this->kehilangan->getTotalKehilangan());
    }
}
