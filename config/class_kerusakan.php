<?php
require_once 'database.php';

class Kerusakan {
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

    // 2. Untuk hapus data kerusakan
    public function hapusKerusakan($id_peminjaman) {
        try {
            $this->conn->beginTransaction();
    
            $sqlHapusStatus = "DELETE FROM status_kerusakan WHERE id_peminjaman = :id_peminjaman";
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
            die("Gagal menghapus data kerusakan: " . $e->getMessage());
        }
    }
    
    // 3. Untuk update data kerusakan
    public function updateKerusakan($id_peminjaman, $id_radio, $nomor_sn, $catatan, $tanggal_baru) {
        try {
            $this->conn->beginTransaction();
            $queryUpdateKerusakan = "UPDATE peminjaman SET id_radio = :id_radio, nomor_sn = :nomor_sn, catatan = :catatan WHERE id_peminjaman = :id_peminjaman";
            $updateKerusakan = $this->conn->prepare($queryUpdateKerusakan);
            $updateKerusakan->bindParam(':id_radio', $id_radio);
            $updateKerusakan->bindParam(':nomor_sn', $nomor_sn);
            $updateKerusakan->bindParam(':catatan', $catatan);
            $updateKerusakan->bindParam(':id_peminjaman', $id_peminjaman);
            $updateKerusakan->execute();
    
            $queryCekData = "SELECT COUNT(*) FROM status_kerusakan WHERE id_peminjaman = :id_peminjaman";
            $dataCek = $this->conn->prepare($queryCekData);
            $dataCek->bindParam(':id_peminjaman', $id_peminjaman);
            $dataCek->execute();
            $exists = $dataCek->fetchColumn() > 0;
    
            if ($exists) {
                $queryUpdateTanggalRusak = "UPDATE status_kerusakan SET tanggal_baru = :tanggal_baru WHERE id_peminjaman = :id_peminjaman";
                $updateTanggalRusak = $this->conn->prepare($queryUpdateTanggalRusak);
            } else {
                $queryUpdateTanggalRusak = "INSERT INTO status_kerusakan (id_peminjaman, tanggal_baru) VALUES (:id_peminjaman, :tanggal_baru)";
                $updateTanggalRusak = $this->conn->prepare($queryUpdateTanggalRusak);
            }
    
            $updateTanggalRusak->bindParam(':tanggal_baru', $tanggal_baru);
            $updateTanggalRusak->bindParam(':id_peminjaman', $id_peminjaman);
            $updateTanggalRusak->execute();
    
            $this->conn->commit();
            return true;
    
        } catch (PDOException $e) {
            $this->conn->rollBack();
            die("Gagal memperbarui data kerusakan: " . $e->getMessage());
        }
    }

    // 4. Untuk menampilkan data kerusakan
    public function getAllDatakerusakan() {
        try {
            $sqltampilkerusakan =
                "SELECT s.tanggal_baru, p.*, m.nama_karyawan, m.nik, m.departemen, r.tipe_radio
                FROM status_kerusakan s
                JOIN peminjaman p ON s.id_peminjaman = p.id_peminjaman
                JOIN manpower m ON p.id_manpower = m.id_manpower
                JOIN radio r ON p.id_radio = r.id_radio
                ORDER BY p.tanggal DESC";
            $menampilkandatakerusakan = $this->conn->prepare($sqltampilkerusakan);
            $menampilkandatakerusakan->execute();
            return $menampilkandatakerusakan->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Gagal mengambil data kerusakan: " . $e->getMessage());
        }
    }

    // 5. Untuk menampilkan data berdasarkan filter tanggal
    public function getDataKerusakanByTanggal($tanggal_mulai = null, $tanggal_selesai = null) {
        try {
            $sqltanggal = "SELECT p.*, s.tanggal_baru, m.nama_karyawan, m.nik, m.departemen, r.tipe_radio 
                           FROM status_kerusakan s
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
            die("Gagal mengambil data kerusakan: " . $e->getMessage());
        }
    }

    // 6. Untuk menghitung total data kerusakan
    public function getTotalKerusakan() {
        try {
            $sqltotalkerusakan = "SELECT COUNT(*) AS total FROM status_kerusakan";
            $totalkerusakan = $this->conn->prepare($sqltotalkerusakan);
            $totalkerusakan->execute();
            $result = $totalkerusakan->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            die("Gagal menghitung total kerusakan: " . $e->getMessage());
        }
    }

}
?>