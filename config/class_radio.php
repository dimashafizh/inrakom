<?php
require_once 'database.php';

class Radio {
    private $conn;

    public function __construct() {
        $this->conn = Database::connect();
    }

    // 1. Untuk tambah data radio
    public function tambahRadio($tipe_radio) {
        try {
            // Karena id_perusahaan-nya selalu 1
            $id_perusahaan = 1;

            $sqltambahradio = "INSERT INTO radio (id_perusahaan, tipe_radio) VALUES (:id_perusahaan, :tipe_radio)";
            $tambahdataradio = $this->conn->prepare($sqltambahradio);
            $tambahdataradio->bindParam(':id_perusahaan', $id_perusahaan);
            $tambahdataradio->bindParam(':tipe_radio', $tipe_radio);
            return $tambahdataradio->execute();
        } catch (PDOException $e) {
            die("Gagal menambahkan data radio: " . $e->getMessage());
        }
    }

    // 2. Untuk hapus data radio
    public function hapusRadio($id_radio) {
        try {
            $sqlhapusradio = "DELETE FROM radio WHERE id_radio = :id_radio";
            $hapusdataradio = $this->conn->prepare($sqlhapusradio);
            $hapusdataradio->bindParam(':id_radio', $id_radio);
            return $hapusdataradio->execute();
        } catch (PDOException $e) {
            if ($e->getCode() == '23000') {
                return 'used';
            }

            die("Gagal menghapus data radio: " . $e->getMessage());
        }
    }

    // 3. Untuk update data radio
    public function updateRadio($id_radio, $tipe_radio) {
        try {
            $queryupdateradio = "UPDATE radio SET tipe_radio = :tipe_radio WHERE id_radio = :id_radio";
            $updatedataradio = $this->conn->prepare($queryupdateradio);
            $updatedataradio->bindParam(':tipe_radio', $tipe_radio);
            $updatedataradio->bindParam(':id_radio', $id_radio);
            return $updatedataradio->execute();
        } catch (PDOException $e) {
            die("Gagal memperbarui data radio: " . $e->getMessage());
        }
        
    }

    // 4. Untuk menampilkan data radio
    public function getAllDataradio() {
        try {
            $sqltampilradio = "SELECT * FROM radio ORDER BY id_radio ASC";
            $menampilkandataradio = $this->conn->prepare($sqltampilradio);
            $menampilkandataradio->execute();
            return $menampilkandataradio->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Gagal mengambil data radio: " . $e->getMessage());
        }
    }

    // 5. Untuk mengecek atau validasi data radio
    public function cekRadio($tipe_radio) {
        try {
            $sqlcekradio = "SELECT COUNT(*) FROM radio WHERE tipe_radio = :tipe_radio";
            $cekradio = $this->conn->prepare($sqlcekradio);
            $cekradio->bindParam(':tipe_radio', $tipe_radio);
            $cekradio->execute();
            $jumlah = $cekradio->fetchColumn();
            return $jumlah > 0;
        } catch (PDOException $e) {
            die("Gagal mengecek S/N radio: " . $e->getMessage());
        }
    }
}
?>