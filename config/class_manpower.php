<?php
require_once 'database.php';

class Manpower {
    private $conn;

    public function __construct() {
        $this->conn = Database::connect();
    }

    // 1. Untuk tambah data manpower
    public function tambahManpower($nama_karyawan, $nik, $departemen) {
        try {
            $sqltambahmanpower = "INSERT INTO manpower (nama_karyawan, nik, departemen) VALUES (:nama_karyawan, :nik, :departemen)";
            $tambahdatamanpower = $this->conn->prepare($sqltambahmanpower);
            $tambahdatamanpower->bindParam(':nama_karyawan', $nama_karyawan);
            $tambahdatamanpower->bindParam(':nik', $nik);
            $tambahdatamanpower->bindParam(':departemen', $departemen);
            return $tambahdatamanpower->execute();
        } catch (PDOException $e) {
            die("Gagal menambahkan data Manpower: " . $e->getMessage());
        }
    }

    // 2. Untuk hapus data manpower
    public function hapusManpower($id_manpower) {
        try {
            $sqlhapusmanpower = "DELETE FROM manpower WHERE id_manpower = :id_manpower";
            $hapusdatamanpower = $this->conn->prepare($sqlhapusmanpower);
            $hapusdatamanpower->bindParam(':id_manpower', $id_manpower);
            return $hapusdatamanpower->execute();
        } catch (PDOException $e) {
            if ($e->getCode() == '23000') {
                return 'used';
            }
            
            die("Gagal menghapus data Manpower: " . $e->getMessage());
        }
    }

    // 3. Untuk update data manpower
    public function updateManpower($id_manpower, $nama_karyawan, $nik, $departemen) {
        try {
            $queryupdatemanpower = "UPDATE manpower SET nama_karyawan = :nama_karyawan, nik = :nik, departemen = :departemen WHERE id_manpower = :id_manpower";
            $updatedatamanpower = $this->conn->prepare($queryupdatemanpower);
            $updatedatamanpower->bindParam(':nama_karyawan', $nama_karyawan);
            $updatedatamanpower->bindParam(':nik', $nik);
            $updatedatamanpower->bindParam(':departemen', $departemen);
            $updatedatamanpower->bindParam(':id_manpower', $id_manpower);
            return $updatedatamanpower->execute();
        } catch (PDOException $e) {
            die("Gagal memperbarui data Manpower: " . $e->getMessage());
        }
        
    }

    // 4. Untuk mengecek atau validasi data nik
    public function cekNIK($nik) {
        try {
            $sqlceknik = "SELECT COUNT(*) FROM manpower WHERE nik = :nik";
            $ceknik = $this->conn->prepare($sqlceknik);
            $ceknik->bindParam(':nik', $nik);
            $ceknik->execute();
            $jumlah = $ceknik->fetchColumn();
            return $jumlah > 0;
        } catch (PDOException $e) {
            die("Gagal mengecek NIK: " . $e->getMessage());
        }
    }

    // 5. Untuk menampilkan data manpower
    public function getAllDatamanpower() {
        try {
            $sqltampilmanpower = "SELECT * FROM manpower ORDER BY id_manpower ASC";
            $menampilkandatamanpower = $this->conn->prepare($sqltampilmanpower);
            $menampilkandatamanpower->execute();
            return $menampilkandatamanpower->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Gagal mengambil data Manpower: " . $e->getMessage());
        }
    }
}
?>