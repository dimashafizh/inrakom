<?php
require_once 'database.php';

class Peminjaman {
    private $conn;

    public function __construct() {
        $this->conn = Database::connect();
    }

    // 1. Untuk tambah data peminjaman
    public function tambahPeminjaman($id_manpower, $id_radio, $nomor_sn, $catatan, $tanggal) {
        try {
            $this->conn->beginTransaction();
            
            $sqltambahpeminjaman = "INSERT INTO peminjaman (id_manpower, id_radio, nomor_sn, catatan, tanggal) VALUES (:id_manpower, :id_radio, :nomor_sn, :catatan, :tanggal)";
            $tambahdatapeminjaman = $this->conn->prepare($sqltambahpeminjaman);
            $tambahdatapeminjaman->bindParam(':id_manpower', $id_manpower);
            $tambahdatapeminjaman->bindParam(':id_radio', $id_radio);
            $tambahdatapeminjaman->bindParam(':nomor_sn', $nomor_sn);
            $tambahdatapeminjaman->bindParam(':catatan', $catatan);
            $tambahdatapeminjaman->bindParam(':tanggal', $tanggal);
            $tambahdatapeminjaman->execute();

            $id_statuspeminjaman = $this->conn->lastInsertId();
    
            $sqlTambahPeminjaman = "INSERT INTO status_peminjaman (id_peminjaman) VALUES (:id_peminjaman)";
            $tambahPeminjaman = $this->conn->prepare($sqlTambahPeminjaman);
            $tambahPeminjaman->bindParam(':id_peminjaman', $id_statuspeminjaman);
            $tambahPeminjaman->execute();

            $this->conn->commit();
            return true;

        } catch (PDOException $e) {
            $this->conn->rollBack();
            die("Gagal menambahkan data peminjaman: " . $e->getMessage());
        }
    }

    // 2. Untuk ambil data dari tabel radio
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

    // 3. Untuk hapus data peminjaman
    public function hapusPeminjaman($id_peminjaman) {
        try {
            $this->conn->beginTransaction();
    
            $sqlHapusStatus = "DELETE FROM status_peminjaman WHERE id_peminjaman = :id_peminjaman";
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
            die("Gagal menghapus data peminjaman: " . $e->getMessage());
        }
    }
    
    // 4. Untuk update data peminjaman
    public function updatePeminjaman($id_peminjaman, $id_radio, $catatan, $tanggal) {
        try {
            $queryupdatepeminjaman = "UPDATE peminjaman SET id_radio = :id_radio, catatan = :catatan, tanggal = :tanggal WHERE id_peminjaman = :id_peminjaman";
            $updatedatapeminjaman = $this->conn->prepare($queryupdatepeminjaman);
            $updatedatapeminjaman->bindParam(':id_radio', $id_radio);
            $updatedatapeminjaman->bindParam(':catatan', $catatan);
            $updatedatapeminjaman->bindParam(':tanggal', $tanggal);
            $updatedatapeminjaman->bindParam(':id_peminjaman', $id_peminjaman);
            return $updatedatapeminjaman->execute();
        } catch (PDOException $e) {
            die("Gagal memperbarui data peminjaman: " . $e->getMessage());
        }
    }
    
    // 5. Untuk update data S/N
    public function updateNomorSN($id_peminjaman, $nomor_sn) {
        try {
            $queryupdatesn = "UPDATE peminjaman SET nomor_sn = :nomor_sn WHERE id_peminjaman = :id_peminjaman";
            $updatedatasn = $this->conn->prepare($queryupdatesn);
            $updatedatasn->bindParam(':nomor_sn', $nomor_sn);
            $updatedatasn->bindParam(':id_peminjaman', $id_peminjaman);
            return $updatedatasn->execute();
        } catch (PDOException $e) {
            die("Gagal memperbarui S/N radio: " . $e->getMessage());
        }
    }

    // 6. Untuk menampilkan data peminjaman
    public function getAllDatapeminjaman() {
        try {
            $sqltampilpeminjaman =
                "SELECT p.*, m.nama_karyawan, m.nik, m.departemen, r.tipe_radio
            FROM status_peminjaman s
            JOIN peminjaman p ON s.id_peminjaman = p.id_peminjaman
            JOIN manpower m ON p.id_manpower = m.id_manpower
            JOIN radio r ON p.id_radio = r.id_radio
            ORDER BY p.tanggal DESC";
            $menampilkandatapeminjaman = $this->conn->prepare($sqltampilpeminjaman);
            $menampilkandatapeminjaman->execute();
            return $menampilkandatapeminjaman->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Gagal mengambil data peminjaman: " . $e->getMessage());
        }
    }

    // 7. Untuk validasi atau pengecekan nomor SN
    public function cekNomorSN($nomor_sn) {
        try {
            $sqlCekSN = "SELECT COUNT(*) 
                    FROM peminjaman p
                    INNER JOIN status_peminjaman sp ON p.id_peminjaman = sp.id_peminjaman
                    WHERE p.nomor_sn = :nomor_sn";
            $cekSN = $this->conn->prepare($sqlCekSN);
            $cekSN->bindParam(':nomor_sn', $nomor_sn);
            $cekSN->execute();
            $jumlah = $cekSN->fetchColumn();
            return $jumlah > 0;
        } catch (PDOException $e) {
            die("Gagal mengecek S/N radio: " . $e->getMessage());
        }
    }

    // 8. Untuk menampilkan data berdasarkan filter tanggal
    public function getDataPeminjamanByTanggal($tanggal_mulai = null, $tanggal_selesai = null) {
    try {
        $sqltanggal = "SELECT p.*, m.nama_karyawan, m.nik, m.departemen, r.tipe_radio 
                       FROM status_peminjaman s
                       JOIN peminjaman p ON s.id_peminjaman = p.id_peminjaman
                       JOIN manpower m ON p.id_manpower = m.id_manpower
                       JOIN radio r ON p.id_radio = r.id_radio";

        if ($tanggal_mulai && $tanggal_selesai) {
            $sqltanggal .= " WHERE p.tanggal BETWEEN :tanggal_mulai AND :tanggal_selesai";
        }

        $sqltanggal .= " ORDER BY p.tanggal DESC";
        $ambiltanggal = $this->conn->prepare($sqltanggal);

        if ($tanggal_mulai && $tanggal_selesai) {
            $ambiltanggal->bindParam(':tanggal_mulai', $tanggal_mulai);
            $ambiltanggal->bindParam(':tanggal_selesai', $tanggal_selesai);
        }

        $ambiltanggal->execute();
        return $ambiltanggal->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Gagal mengambil data peminjaman: " . $e->getMessage());
    }
}

    // 9. Untuk menghitung total data peminjaman
    public function getTotalPeminjaman() {
        try {
            $sqltotalpeminjaman = "SELECT COUNT(*) AS total FROM status_peminjaman";
            $totalpeminjaman = $this->conn->prepare($sqltotalpeminjaman);
            $totalpeminjaman->execute();
            $result = $totalpeminjaman->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            die("Gagal menghitung total peminjaman: " . $e->getMessage());
        }
    }

    // 10. Untuk mengubah data peminjaman ke pengembalian
    public function pindahKePengembalian($id_peminjaman, $catatan, $tanggal_baru) {
        try {
            $this->conn->beginTransaction();
    
            $sqlUpdatePengembalian = "UPDATE peminjaman SET catatan = :catatan WHERE id_peminjaman = :id_peminjaman";
            $updatePengembalian = $this->conn->prepare($sqlUpdatePengembalian);
            $updatePengembalian->bindParam(':catatan', $catatan);
            $updatePengembalian->bindParam(':id_peminjaman', $id_peminjaman);
            $updatePengembalian->execute();
    
            $sqlCek = "SELECT id_peminjaman FROM status_pengembalian WHERE id_peminjaman = :id_peminjaman";
            $cekStatus = $this->conn->prepare($sqlCek);
            $cekStatus->bindParam(':id_peminjaman', $id_peminjaman);
            $cekStatus->execute();
            $data = $cekStatus->fetch(PDO::FETCH_ASSOC);

            if (!$data) {
                $sqlPindahPengembalian = "INSERT INTO status_pengembalian (id_peminjaman, tanggal_baru) VALUES (:id_peminjaman, :tanggal_baru)";
                $pindahPengembalian = $this->conn->prepare($sqlPindahPengembalian);
                $pindahPengembalian->bindParam(':id_peminjaman', $id_peminjaman);
                $pindahPengembalian->bindParam(':tanggal_baru', $tanggal_baru);
                $pindahPengembalian->execute();
            
                $sqlHapusStatus = "DELETE FROM status_peminjaman WHERE id_peminjaman = :id_peminjaman";
                $hapusStatus = $this->conn->prepare($sqlHapusStatus);
                $hapusStatus->bindParam(':id_peminjaman', $id_peminjaman);
                $hapusStatus->execute();
            
                $this->conn->commit();
                return true;
            } else {
                $this->conn->rollBack();
                return false;
            }
    
        } catch (PDOException $e) {
            $this->conn->rollBack();
            die("Gagal memindahkan dan memperbarui data: " . $e->getMessage());
        }
    }

    // 11. Untuk mengubah data peminjaman ke kerusakan
    public function pindahKeKerusakan($id_peminjaman, $catatan, $tanggal_baru) {
        try {
            $this->conn->beginTransaction();
    
            $sqlUpdateKerusakan = "UPDATE peminjaman SET catatan = :catatan WHERE id_peminjaman = :id_peminjaman";
            $updateKerusakan = $this->conn->prepare($sqlUpdateKerusakan);
            $updateKerusakan->bindParam(':catatan', $catatan);
            $updateKerusakan->bindParam(':id_peminjaman', $id_peminjaman);
            $updateKerusakan->execute();
    
            $sqlCek = "SELECT id_peminjaman FROM status_kerusakan WHERE id_peminjaman = :id_peminjaman";
            $cekStatus = $this->conn->prepare($sqlCek);
            $cekStatus->bindParam(':id_peminjaman', $id_peminjaman);
            $cekStatus->execute();
            $data = $cekStatus->fetch(PDO::FETCH_ASSOC);

            if (!$data) {
                $sqlPindahKerusakan = "INSERT INTO status_kerusakan (id_peminjaman, tanggal_baru) VALUES (:id_peminjaman, :tanggal_baru)";
                $pindahKerusakan = $this->conn->prepare($sqlPindahKerusakan);
                $pindahKerusakan->bindParam(':id_peminjaman', $id_peminjaman);
                $pindahKerusakan->bindParam(':tanggal_baru', $tanggal_baru);
                $pindahKerusakan->execute();
            
                $sqlHapusStatus = "DELETE FROM status_peminjaman WHERE id_peminjaman = :id_peminjaman";
                $hapusStatus = $this->conn->prepare($sqlHapusStatus);
                $hapusStatus->bindParam(':id_peminjaman', $id_peminjaman);
                $hapusStatus->execute();
            
                $this->conn->commit();
                return true;
            } else {
                $this->conn->rollBack();
                return false;
            }
    
        } catch (PDOException $e) {
            $this->conn->rollBack();
            die("Gagal memindahkan dan memperbarui data: " . $e->getMessage());
        }
    }

    // 12. Untuk mengubah data peminjaman ke kehilangan
    public function pindahKeKehilangan($id_peminjaman, $catatan, $tanggal_baru) {
        try {
            $this->conn->beginTransaction();
    
            $sqlUpdateKehilangan = "UPDATE peminjaman SET catatan = :catatan WHERE id_peminjaman = :id_peminjaman";
            $updateKehilangan = $this->conn->prepare($sqlUpdateKehilangan);
            $updateKehilangan->bindParam(':catatan', $catatan);
            $updateKehilangan->bindParam(':id_peminjaman', $id_peminjaman);
            $updateKehilangan->execute();
    
            $sqlCek = "SELECT id_peminjaman FROM status_kehilangan WHERE id_peminjaman = :id_peminjaman";
            $cekStatus = $this->conn->prepare($sqlCek);
            $cekStatus->bindParam(':id_peminjaman', $id_peminjaman);
            $cekStatus->execute();
            $data = $cekStatus->fetch(PDO::FETCH_ASSOC);

            if (!$data) {
                $sqlPindahKehilangan = "INSERT INTO status_kehilangan (id_peminjaman, tanggal_baru) VALUES (:id_peminjaman, :tanggal_baru)";
                $pindahKehilangan = $this->conn->prepare($sqlPindahKehilangan);
                $pindahKehilangan->bindParam(':id_peminjaman', $id_peminjaman);
                $pindahKehilangan->bindParam(':tanggal_baru', $tanggal_baru);
                $pindahKehilangan->execute();
            
                $sqlHapusStatus = "DELETE FROM status_peminjaman WHERE id_peminjaman = :id_peminjaman";
                $hapusStatus = $this->conn->prepare($sqlHapusStatus);
                $hapusStatus->bindParam(':id_peminjaman', $id_peminjaman);
                $hapusStatus->execute();
            
                $this->conn->commit();
                return true;
            } else {
                $this->conn->rollBack();
                return false;
            }
    
        } catch (PDOException $e) {
            $this->conn->rollBack();
            die("Gagal memindahkan dan memperbarui data: " . $e->getMessage());
        }
    }

}
?>