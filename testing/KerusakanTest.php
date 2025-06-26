<?php
use PHPUnit\Framework\TestCase;

require_once 'class/classKerusakan.php';

class DatabaseMock {
    public static $pdo;

    public static function connect() {
        return self::$pdo;
    }
}

class KerusakanMock extends Kerusakan {
    public function __construct() {
        $this->conn = DatabaseMock::connect();
    }
}

class KerusakanTest extends TestCase {
    private $pdo;
    private $stmt;
    private $kerusakan;

    protected function setUp(): void {
        $this->pdo = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);

        DatabaseMock::$pdo = $this->pdo;
        $this->kerusakan = new KerusakanMock();
    }

    // 1. Untuk test ambil data dari tabel radio
    public function testGetRadioList() {
        $expected = [['id_radio' => 1, 'tipe_radio' => 'Hytera PT568']];
        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->stmt->method('execute');
        $this->stmt->method('fetchAll')->willReturn($expected);

        $result = $this->kerusakan->getRadioList();
        $this->assertEquals($expected, $result);
    }

    // 2. Untuk test hapus data kerusakan
    public function testHapusKerusakan() {
        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->stmt->method('execute')->willReturn(true);
        $this->pdo->expects($this->once())->method('beginTransaction');
        $this->pdo->expects($this->once())->method('commit');

        $result = $this->kerusakan->hapusKerusakan(1);
        $this->assertTrue($result);
    }

    // 3. Untuk test update data kerusakan
    public function testUpdateKerusakan() {
        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->stmt->method('execute')->willReturn(true);
        $this->stmt->method('fetchColumn')->willReturn(1);

        $this->pdo->expects($this->once())->method('beginTransaction');
        $this->pdo->expects($this->once())->method('commit');

        $result = $this->kerusakan->updateKerusakan(1, 1, '222ABCD111', 'Antena rusak', '2025-07-01');
        $this->assertTrue($result);
    }

    // 4. Untuk test menampilkan data kerusakan
    public function testGetAllDatakerusakan() {
        $expected = [['id_peminjaman' => 1, 'nama_karyawan' => 'Santoso']];
        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->stmt->method('execute');
        $this->stmt->method('fetchAll')->willReturn($expected);

        $result = $this->kerusakan->getAllDatakerusakan();
        $this->assertEquals($expected, $result);
    }

    // 5. Untuk test menampilkan data berdasarkan filter tanggal
    public function testGetDataKerusakanByTanggal() {
        $expected = [['id_peminjaman' => 1, 'tanggal_baru' => '2025-07-01']];
        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->stmt->method('execute');
        $this->stmt->method('fetchAll')->willReturn($expected);

        $result = $this->kerusakan->getDataKerusakanByTanggal('2025-06-25', '2025-07-10');
        $this->assertEquals($expected, $result);
    }

    // 6. Untuk test menghitung total data kerusakan
    public function testGetTotalKerusakan() {
        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->stmt->method('execute');
        $this->stmt->method('fetch')->willReturn(['total' => 3]);

        $this->assertEquals(3, $this->kerusakan->getTotalKerusakan());
    }
}
