<?php
require_once 'config/class_peminjaman.php';
require_once 'config/class_login.php';
$login = new Login();
$peminjaman = new Peminjaman();
$dataPeminjaman = $peminjaman->getAllDatapeminjaman();

// Untuk menampilkan data berdasarkan filter tanggal
if (isset($_POST['filter_tanggal'])) {
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_selesai = $_POST['tanggal_selesai'];
    $dataPeminjaman = $peminjaman->getDataPeminjamanByTanggal($tanggal_mulai, $tanggal_selesai);
} else {
    $dataPeminjaman = $peminjaman->getDataPeminjamanByTanggal();
}

// Untuk validasi login
if (!$login->isLoggedIn()) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="images/Logo-BUMA1.png" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.2/css/buttons.dataTables.css">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <title>Laporan Data Peminjaman</title>
</head>
<body>

            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h1 class="mt-1"><i class="fa-solid fa-circle-up"></i> Export Data Peminjaman Radio Komunikasi</h1>
                            </div>
                            <div class="card-body">
                            <div class="row mt-1">
                                    <form method="post" class="d-flex align-items-end gap-2">
                                        <div class="form-group">
                                            <input type="date" id="tanggal_mulai" name="tanggal_mulai" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="date" id="tanggal_selesai" name="tanggal_selesai" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" name="filter_tanggal" class="btn btn-secondary">Filter</button>
                                        </div>
                                    </form>
                                </div>
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Karyawan</th>
                                            <th>NIK</th>
                                            <th>Departemen</th>
                                            <th>Tipe Radio HT</th>
                                            <th>S/N</th>
                                            <th>Catatan</th>
                                            <th>Tanggal Pinjam</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $no = 1;
                                        foreach ($dataPeminjaman as $row) {
                                            echo "<tr>";
                                                echo "<td>" . $no++ . "</td>";
                                                echo "<td>" . htmlspecialchars($row['nama_karyawan']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['nik']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['departemen']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['tipe_radio']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['nomor_sn']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['catatan']) . "</td>";
                                                echo "<td>" . date('d-m-Y', strtotime($row['tanggal'])) . "</td>";
                                                
                                            echo "</tr>";
                                        }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </main>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
            <script src="js/scripts.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
            <script src="assets/demo/chart-area-demo.js"></script>
            <script src="assets/demo/chart-bar-demo.js"></script>
            
            <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
            <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
            <script src="https://cdn.datatables.net/buttons/3.2.2/js/dataTables.buttons.js"></script>
            <script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.dataTables.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
            <script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.html5.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.print.min.js"></script>

            <script>
                new DataTable('#datatablesSimple', {
                layout: {
                    topStart: {
                        buttons: ['excel', 'pdf', 'print']
                    }
                }
            });
            </script>
</body>
</html>