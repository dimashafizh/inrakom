<?php
require_once 'database.php';

class Kehilangan {
    private $conn;

    public function __construct() {
        $this->conn = Database::connect();
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

    // 2. Untuk hapus data kehilangan
    public function hapusKehilangan($id_peminjaman) {
        try {
            $this->conn->beginTransaction();
    
            $sqlHapusStatus = "DELETE FROM status_kehilangan WHERE id_peminjaman = :id_peminjaman";
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
            die("Gagal menghapus data kehilangan: " . $e->getMessage());
        }
    }
    
    // 3. Untuk update data kehilangan
    public function updateKehilangan($id_peminjaman, $id_radio, $nomor_sn, $catatan, $tanggal_baru) {
        try {
            $this->conn->beginTransaction();
            $queryUpdateKehilangan = "UPDATE peminjaman SET id_radio = :id_radio, nomor_sn = :nomor_sn, catatan = :catatan WHERE id_peminjaman = :id_peminjaman";
            $updateKehilangan = $this->conn->prepare($queryUpdateKehilangan);
            $updateKehilangan->bindParam(':id_radio', $id_radio);
            $updateKehilangan->bindParam(':nomor_sn', $nomor_sn);
            $updateKehilangan->bindParam(':catatan', $catatan);
            $updateKehilangan->bindParam(':id_peminjaman', $id_peminjaman);
            $updateKehilangan->execute();
    
            $queryCekData = "SELECT COUNT(*) FROM status_kehilangan WHERE id_peminjaman = :id_peminjaman";
            $dataCek = $this->conn->prepare($queryCekData);
            $dataCek->bindParam(':id_peminjaman', $id_peminjaman);
            $dataCek->execute();
            $exists = $dataCek->fetchColumn() > 0;
    
            if ($exists) {
                $queryUpdateTanggalHilang = "UPDATE status_kehilangan SET tanggal_baru = :tanggal_baru WHERE id_peminjaman = :id_peminjaman";
                $updateTanggalHilang = $this->conn->prepare($queryUpdateTanggalHilang);
            } else {
                $queryUpdateTanggalHilang = "INSERT INTO status_kehilangan (id_peminjaman, tanggal_baru) VALUES (:id_peminjaman, :tanggal_baru)";
                $updateTanggalHilang = $this->conn->prepare($queryUpdateTanggalHilang);
            }
    
            $updateTanggalHilang->bindParam(':tanggal_baru', $tanggal_baru);
            $updateTanggalHilang->bindParam(':id_peminjaman', $id_peminjaman);
            $updateTanggalHilang->execute();
    
            $this->conn->commit();
            return true;
    
        } catch (PDOException $e) {
            $this->conn->rollBack();
            die("Gagal memperbarui data kerusakan: " . $e->getMessage());
        }
    }

    // 4. Untuk menampilkan data kehilangan
    public function getAllDatakehilangan() {
        try {
            $sqltampilkehilangan =
                "SELECT s.tanggal_baru, p.*, m.nama_karyawan, m.nik, m.departemen, r.tipe_radio
                FROM status_kehilangan s
                JOIN peminjaman p ON s.id_peminjaman = p.id_peminjaman
                JOIN manpower m ON p.id_manpower = m.id_manpower
                JOIN radio r ON p.id_radio = r.id_radio
                ORDER BY p.tanggal DESC";
            $menampilkandatakehilangan = $this->conn->prepare($sqltampilkehilangan);
            $menampilkandatakehilangan->execute();
            return $menampilkandatakehilangan->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Gagal mengambil data kehilangan: " . $e->getMessage());
        }
    }

    // 5. Untuk menampilkan data berdasarkan filter tanggal
    public function getDataKehilanganByTanggal($tanggal_mulai = null, $tanggal_selesai = null) {
        try {
            $sqltanggal = "SELECT p.*, s.tanggal_baru, m.nama_karyawan, m.nik, m.departemen, r.tipe_radio 
                           FROM status_kehilangan s
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
            die("Gagal mengambil data kehilangan: " . $e->getMessage());
        }
    }

    // 6. Untuk menghitung total data kehilangan
    public function getTotalKehilangan() {
        try {
            $sqltotalkehilangan = "SELECT COUNT(*) AS total FROM status_kehilangan";
            $totalkehilangan = $this->conn->prepare($sqltotalkehilangan);
            $totalkehilangan->execute();
            $result = $totalkehilangan->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            die("Gagal menghitung total kehilangan: " . $e->getMessage());
        }
    }

}
?>