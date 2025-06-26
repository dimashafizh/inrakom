<?php
use PHPUnit\Framework\TestCase;

require_once 'class/classPeminjaman.php';

class DatabaseMock {
    public static $pdo;

    public static function connect() {
        return self::$pdo;
    }
}

class PeminjamanMock extends Peminjaman {
    public function __construct() {
        $this->conn = DatabaseMock::connect();
    }
}

class PeminjamanTest extends TestCase {
    private $pdo;
    private $stmt;
    private $peminjaman;

    protected function setUp(): void {
        $this->pdo = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);

        DatabaseMock::$pdo = $this->pdo;
        $this->peminjaman = new PeminjamanMock();
    }

    // 1. Untuk test tambah data peminjaman
    public function testTambahPeminjaman() {
        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->stmt->method('execute')->willReturn(true);

        $this->pdo->method('lastInsertId')->willReturn('10');
        $this->pdo->expects($this->once())->method('beginTransaction');
        $this->pdo->expects($this->once())->method('commit');

        $result = $this->peminjaman->tambahPeminjaman(1, 1, '111ABCD222', 'Radio baru', '2025-06-20');
        $this->assertTrue($result);
    }

    // 2. Untuk test ambil data dari tabel radio
    public function testGetRadioList() {
        $expected = [['id_radio' => 1, 'tipe_radio' => 'Hytera PT568']];
        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->stmt->method('execute');
        $this->stmt->method('fetchAll')->willReturn($expected);

        $result = $this->peminjaman->getRadioList();
        $this->assertEquals($expected, $result);
    }

    // 3. Untuk test hapus data peminjaman
    public function testHapusPeminjaman() {
        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->stmt->method('execute')->willReturn(true);
        $this->pdo->expects($this->once())->method('beginTransaction');
        $this->pdo->expects($this->once())->method('commit');

        $result = $this->peminjaman->hapusPeminjaman(1);
        $this->assertTrue($result);
    }

    // 4. Untuk test update data peminjaman
    public function testUpdatePeminjaman() {
        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->stmt->method('execute')->willReturn(true);

        $result = $this->peminjaman->updatePeminjaman(1, 1, 'Radio second', '2025-06-25');
        $this->assertTrue($result);
    }

    // 5. Untuk test update data S/N
    public function testUpdateNomorSN() {
        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->stmt->method('execute')->willReturn(true);

        $result = $this->peminjaman->updateNomorSN(1, '222ABCD111');
        $this->assertTrue($result);
    }

    // 6. Untuk test menampilkan data peminjaman
    public function testGetAllDatapeminjaman() {
        $expected = [['id_peminjaman' => 1, 'nama_karyawan' => 'Santoso']];
        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->stmt->method('execute');
        $this->stmt->method('fetchAll')->willReturn($expected);

        $result = $this->peminjaman->getAllDatapeminjaman();
        $this->assertEquals($expected, $result);
    }

    // 7. Untuk test validasi atau pengecekan nomor SN
    public function testCekNomorSNTrue() {
        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->stmt->method('execute');
        $this->stmt->method('fetchColumn')->willReturn(1);

        $this->assertTrue($this->peminjaman->cekNomorSN('222ABCD111'));
    }

    public function testCekNomorSNFalse() {
        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->stmt->method('execute');
        $this->stmt->method('fetchColumn')->willReturn(0);

        $this->assertFalse($this->peminjaman->cekNomorSN('123ABCD123'));
    }

    // 8. Untuk test menampilkan data berdasarkan filter tanggal
    public function testGetDataPeminjamanByTanggal() {
        $expected = [['id_peminjaman' => 1, 'tanggal' => '2025-06-25']];
        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->stmt->method('execute');
        $this->stmt->method('fetchAll')->willReturn($expected);

        $result = $this->peminjaman->getDataPeminjamanByTanggal('2025-06-20', '2025-06-30');
        $this->assertEquals($expected, $result);
    }

    // 9. Untuk test menghitung total data peminjaman
    public function testGetTotalPeminjaman() {
        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->stmt->method('execute');
        $this->stmt->method('fetch')->willReturn(['total' => 5]);

        $this->assertEquals(5, $this->peminjaman->getTotalPeminjaman());
    }

    // 10. Untuk test mengubah data peminjaman ke pengembalian
    public function testPindahKePengembalian() {
        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->stmt->method('execute')->willReturn(true);
        $this->stmt->method('fetch')->willReturn(false);

        $this->pdo->expects($this->once())->method('beginTransaction');
        $this->pdo->expects($this->once())->method('commit');

        $result = $this->peminjaman->pindahKePengembalian(1, 'Radio second', '2025-06-30');
        $this->assertTrue($result);
    }

    // 11. Untuk test mengubah data peminjaman ke kerusakan
    public function testPindahKeKerusakan() {
        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->stmt->method('execute')->willReturn(true);
        $this->stmt->method('fetch')->willReturn(false);

        $this->pdo->expects($this->once())->method('beginTransaction');
        $this->pdo->expects($this->once())->method('commit');

        $result = $this->peminjaman->pindahKeKerusakan(2, 'Antena rusak', '2025-06-30');
        $this->assertTrue($result);
    }

    // 12. Untuk test mengubah data peminjaman ke kehilangan
    public function testPindahKeKehilangan() {
        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->stmt->method('execute')->willReturn(true);
        $this->stmt->method('fetch')->willReturn(false);

        $this->pdo->expects($this->once())->method('beginTransaction');
        $this->pdo->expects($this->once())->method('commit');

        $result = $this->peminjaman->pindahKeKehilangan(3, 'Hilang di lapangan', '2025-06-30');
        $this->assertTrue($result);
    }
}
