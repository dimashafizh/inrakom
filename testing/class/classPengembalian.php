<?php
require_once 'database.php';

class Pengembalian {
    protected $conn;

    public function __construct($conn = null) {
        $this->conn = $conn ?? Database::connect();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // 1. Untuk ambil data dari tabel radio
    public function getRadioList() {
        try {
            $sqlambildataradio = "SELECT id_radio, tipe_radio FROM radio";
            $ambildataradio = $this->conn->prepare($sqlambildataradio);
            $ambildataradio->execute();
            return $ambildataradio->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Gagal mengambil data radio: " . $e->getMessage());
        }
    }

    // 2. Untuk hapus data pengembalian
    public function hapusPengembalian($id_peminjaman) {
        try {
            $this->conn->beginTransaction();
    
            $sqlHapusStatus = "DELETE FROM status_pengembalian WHERE id_peminjaman = :id_peminjaman";
            $hapusStatus = $this->conn->prepare($sqlHapusStatus);
            $hapusStatus->bindParam(':id_peminjaman', $id_peminjaman);
            $hapusStatus->execute();
    
            $sqlHapusPeminjaman = "DELETE FROM peminjaman WHERE id_peminjaman = :id_peminjaman";
            $hapusPeminjaman = $this->conn->prepare($sqlHapusPeminjaman);
            $hapusPeminjaman->bindParam(':id_peminjaman', $id_peminjaman);
            $hapusPeminjaman->execute();
    
            $this->conn->commit();
            return true;
    
        } catch (PDOException $e) {
            $this->conn->rollBack();
            die("Gagal menghapus data pengembalian: " . $e->getMessage());
        }
    }
    
    // 3. Untuk update data pengembalian
    public function updatePengembalian($id_peminjaman, $id_radio, $nomor_sn, $catatan, $tanggal_baru) {
        try {
            $this->conn->beginTransaction();
            $queryUpdatePengembalian = "UPDATE peminjaman SET id_radio = :id_radio, nomor_sn = :nomor_sn, catatan = :catatan WHERE id_peminjaman = :id_peminjaman";
            $updatePengembalian = $this->conn->prepare($queryUpdatePengembalian);
            $updatePengembalian->bindParam(':id_radio', $id_radio);
            $updatePengembalian->bindParam(':nomor_sn', $nomor_sn);
            $updatePengembalian->bindParam(':catatan', $catatan);
            $updatePengembalian->bindParam(':id_peminjaman', $id_peminjaman);
            $updatePengembalian->execute();
    
            $queryCekData = "SELECT COUNT(*) FROM status_pengembalian WHERE id_peminjaman = :id_peminjaman";
            $dataCek = $this->conn->prepare($queryCekData);
            $dataCek->bindParam(':id_peminjaman', $id_peminjaman);
            $dataCek->execute();
            $exists = $dataCek->fetchColumn() > 0;
    
            if ($exists) {
                $queryUpdateTanggalKembali = "UPDATE status_pengembalian SET tanggal_baru = :tanggal_baru WHERE id_peminjaman = :id_peminjaman";
                $updateTanggalKembali = $this->conn->prepare($queryUpdateTanggalKembali);
            } else {
                $queryUpdateTanggalKembali = "INSERT INTO status_pengembalian (id_peminjaman, tanggal_baru) VALUES (:id_peminjaman, :tanggal_baru)";
                $updateTanggalKembali = $this->conn->prepare($queryUpdateTanggalKembali);
            }
    
            $updateTanggalKembali->bindParam(':tanggal_baru', $tanggal_baru);
            $updateTanggalKembali->bindParam(':id_peminjaman', $id_peminjaman);
            $updateTanggalKembali->execute();
    
            $this->conn->commit();
            return true;
    
        } catch (PDOException $e) {
            $this->conn->rollBack();
            die("Gagal memperbarui data pengembalian: " . $e->getMessage());
        }
    }
    
    // 4. Untuk menampilkan data pengembalian
    public function getAllDatapengembalian() {
    try {
        $sqltampilpengembalian =
            "SELECT s.tanggal_baru, p.*, m.nama_karyawan, m.nik, m.departemen, r.tipe_radio
            FROM status_pengembalian s
            JOIN peminjaman p ON s.id_peminjaman = p.id_peminjaman
            JOIN manpower m ON p.id_manpower = m.id_manpower
            JOIN radio r ON p.id_radio = r.id_radio
            ORDER BY p.tanggal DESC";
        $menampilkandatapengembalian = $this->conn->prepare($sqltampilpengembalian);
        $menampilkandatapengembalian->execute();
        return $menampilkandatapengembalian->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Gagal mengambil data pengembalian: " . $e->getMessage());
    }
}

    // 5. Untuk menampilkan data berdasarkan filter tanggal
    public function getDataPengembalianByTanggal($tanggal_mulai = null, $tanggal_selesai = null) {
        try {
            $sqltanggal = "SELECT p.*, s.tanggal_baru, m.nama_karyawan, m.nik, m.departemen, r.tipe_radio 
                           FROM status_pengembalian s
                           JOIN peminjaman p ON s.id_peminjaman = p.id_peminjaman
                           JOIN manpower m ON p.id_manpower = m.id_manpower
                           JOIN radio r ON p.id_radio = r.id_radio";
    
            if ($tanggal_mulai && $tanggal_selesai) {
                $sqltanggal .= " WHERE s.tanggal_baru BETWEEN :tanggal_mulai AND :tanggal_selesai";
            }
    
            $sqltanggal .= " ORDER BY s.tanggal_baru DESC";
            $ambiltanggal = $this->conn->prepare($sqltanggal);
    
            if ($tanggal_mulai && $tanggal_selesai) {
                $ambiltanggal->bindParam(':tanggal_mulai', $tanggal_mulai);
                $ambiltanggal->bindParam(':tanggal_selesai', $tanggal_selesai);
            }
    
            $ambiltanggal->execute();
            return $ambiltanggal->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Gagal mengambil data pengembalian: " . $e->getMessage());
        }
    }

    // 6. Untuk menghitung total data pengembalian
    public function getTotalPengembalian() {
        try {
            $sqltotalpengembalian = "SELECT COUNT(*) AS total FROM status_pengembalian";
            $totalpengembalian = $this->conn->prepare($sqltotalpengembalian);
            $totalpengembalian->execute();
            $result = $totalpengembalian->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            die("Gagal menghitung total pengembalian: " . $e->getMessage());
        }
    }

}
?>