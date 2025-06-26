<?php
require_once 'config/class_pengembalian.php';
require_once 'config/class_login.php';
$login = new Login();
$pengembalian = new Pengembalian();
$dataPengembalian = $pengembalian->getAllDatapengembalian();
$radioList = $pengembalian->getRadioList();

// Untuk update informasi pengembalian
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_pengembalian'])) {
    $id_peminjaman = $_POST['id_peminjaman'];
    $id_radio = $_POST['id_radio'];
    $nomor_sn = $_POST['nomor_sn'];
    $catatan = $_POST['catatan'];
    $tanggal_baru = $_POST['tanggal_baru'];

    if ($pengembalian->updatePengembalian($id_peminjaman, $id_radio, $nomor_sn, $catatan, $tanggal_baru)) {
        $_SESSION['alert'] = ['type' => 'primary', 'message' => 'Berhasil memperbarui data pengembalian'];
        header("Location: pengembalian.php");
        exit;
    } else {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Gagal memperbarui data pengembalian'];
    }
}

// Untuk hapus data pengembalian
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['hapus_pengembalian'])) {
    $id_peminjaman = $_POST['id_peminjaman'];
    $hapus = $pengembalian->hapusPengembalian($id_peminjaman);

    if ($hapus === true) {
        $_SESSION['alert'] = ['type' => 'dark', 'message' => 'Data pengembalian telah dihapus!'];
        header("Location: pengembalian.php");
        exit;
    } elseif ($hapus === 'used') {
        $_SESSION['alert'] = ['type' => 'warning', 'message' => 'Data pengembalian tidak bisa dihapus karena sedang digunakan dalam tabel pendataan'];
        header("Location: pengembalian.php");
        exit;
    } else {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Gagal menghapus data pengembalian'];
    }
}

// Untuk menampilkan data berdasarkan filter tanggal
if (isset($_POST['filter_tanggal'])) {
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_selesai = $_POST['tanggal_selesai'];
    $dataPengembalian = $pengembalian->getDataPengembalianByTanggal($tanggal_mulai, $tanggal_selesai);
} else {
    $dataPengembalian = $pengembalian->getDataPengembalianByTanggal();
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
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <link rel="icon" href="images/Logo-BUMA1.png" type="image/png">
        <title>Pengembalian | INRAKOM BINSUA</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed bg-utama">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-navbaratas">

<!-- 1. NAVBAR BRAND -->
        <a class="navbar-brand ps-4 " href="index.php"><img src="images/LOGO-Aplikasi.png" alt="Logo Aplikasi" style="width: 140px; height: 40px;"></a>

    <!-- 1.1 bagian sidebar toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        </nav>

<!-- 2. BAGIAN MENU -->         
        <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <a class="nav-link" href="index.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-grip"></i></div>
                                Dashboard
                            </a>
                            <a class="nav-link" href="peminjaman.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-circle-up"></i></div>
                                Peminjaman
                            </a>
                            <a class="nav-link" href="pengembalian.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-circle-down"></i></div>
                                Pengembalian
                            </a>
                            <a class="nav-link" href="kerusakan.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-screwdriver-wrench"></i></div>
                                Kerusakan
                            </a>
                            <a class="nav-link" href="kehilangan.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-ban"></i></div>
                                Kehilangan
                            </a>
                            <a class="nav-link" href="kelolaradio.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-walkie-talkie"></i></div>
                                Kelola Radio
                            </a>
                            <a class="nav-link" href="manpower.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-users"></i></div>
                                Kelola Manpower
                            </a>
                            <a class="nav-link" href="admin.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-circle-user"></i></div>
                                Kelola Admin
                            </a>
                            <a class="nav-link" href="logout.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-door-open"></i></div>
                                Logout
                            </a>
                        </div>
                    </div>
                </nav>
            </div>

<!-- 3. BAGIAN ISI -->
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <h1 class="mt-4"><i class="fa-solid fa-circle-down"></i> Data Pengembalian Radio Komunikasi</h1>
                        
                        <div class="card mb-4">
                            <div class="card-header">
                                <a href="exportdata-pengembalian.php" class="btn btn-secondary">Export Data</a>
                                <br>
                                <div class="row mt-4">
                                    <div class="mb-3">
                                        <form method="post" class="d-flex align-items-end gap-2">
                                            <div class="form-group">
                                                <input type="date" id="tanggal_mulai" name="tanggal_mulai" class="form-control" required>
                                            </div>
                                            <div class="form-group">
                                                <input type="date" id="tanggal_selesai" name="tanggal_selesai" class="form-control" required>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" name="filter_tanggal" class="btn btn-primary">Filter</button>
                                            </div>
                                        </form>

                                        <?php if (isset($_SESSION['alert'])) : ?>
                                            <div class="alert alert-<?= $_SESSION['alert']['type']; ?> alert-dismissible fade show mt-3" role="alert">
                                                <?= $_SESSION['alert']['message']; ?>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>
                                            <?php unset($_SESSION['alert']); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
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
                                            <th>Tanggal Kembali</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $no = 1;
                                        foreach ($dataPengembalian as $row) {
                                            echo "<tr>";
                                                echo "<td>" . $no++ . "</td>";
                                                echo "<td>" . htmlspecialchars($row['nama_karyawan']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['nik']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['departemen']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['tipe_radio']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['nomor_sn']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['catatan']) . "</td>";
                                                echo "<td>" . date('d-m-Y', strtotime($row['tanggal'])) . "</td>";
                                                echo "<td>" . date('d-m-Y', strtotime($row['tanggal_baru'])) . "</td>";
                                                echo "<td>
                                                        <button type='button' class='btn btn-warning mb-1' data-bs-toggle='modal' data-bs-target='#edit-{$row['id_peminjaman']}'>Edit</button>
                                                        <button type='button' class='btn btn-danger mb-1' data-bs-toggle='modal' data-bs-target='#hapus-{$row['id_peminjaman']}'>Hapus</button>
                                                        
                                                    </td>";
                                            echo "</tr>";

                                            // Modal Edit untuk data pengembalian
                                            echo "
                                            <div class='modal fade' id='edit-{$row['id_peminjaman']}'>
                                                <div class='modal-dialog'>
                                                    <div class='modal-content'>
                                                        <div class='modal-header'>
                                                            <h4 class='modal-title'><i class='fa-solid fa-pen-to-square'></i> Edit Data Pengembalian</h4>
                                                            <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                                                        </div>
                                                        <form method='POST' action=''>
                                                            <div class='modal-body'>

                                                                <label>Tipe Radio HT</label>
                                                                <select name='id_radio' class='form-control' required>";
                                                                    foreach ($radioList as $radio) {
                                                                        $selected = ($radio['id_radio'] == $row['id_radio']) ? 'selected' : '';
                                                                        echo "<option value='" . htmlspecialchars($radio['id_radio']) . "' $selected>" . htmlspecialchars($radio['tipe_radio']) . "</option>";
                                                                    }
                                            echo "              </select>

                                                                <label>S/N Perangkat</label>
                                                                <input type='text' name='nomor_sn' class='form-control' value='" . htmlspecialchars($row['nomor_sn']) . "' required>
                                                                <input type='hidden' name='id_peminjaman' value='{$row['id_peminjaman']}'>
                                                                
                                                                <label>Catatan</label>
                                                                <input type='text' name='catatan' class='form-control' value='" . htmlspecialchars($row['catatan']) . "' required>
                                                                <input type='hidden' name='id_peminjaman' value='{$row['id_peminjaman']}'>

                                                                <label>Tanggal Pengembalian</label>
                                                                <input type='date' name='tanggal_baru' class='form-control' value='" . date('Y-m-d', strtotime($row['tanggal_baru'])) . "' required>
                                                            </div>
                                                            <div class='modal-footer'>
                                                                <button type='submit' name='update_pengembalian' class='btn btn-primary'>Update</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>";

                                            // Modal untuk hapus data pengembalian
                                            echo "
                                            <div class='modal fade' id='hapus-{$row['id_peminjaman']}'>
                                                <div class='modal-dialog'>
                                                    <div class='modal-content'>
                                                        <div class='modal-header'>
                                                            <h4 class='modal-title'><i class='fa-solid fa-triangle-exclamation'></i> Konfirmasi Hapus Data Pengembalian</h4>
                                                            <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                                                        </div>

                                                        <div class='modal-body'>
                                                            <label>Nama Karyawan</label>
                                                            <input type='text' class='form-control' value='{$row['nama_karyawan']}' readonly>
                                                                
                                                            <label>Tipe Radio HT</label>
                                                            <input type='text' class='form-control' value='{$row['tipe_radio']}' readonly>
                                                                
                                                            <label>S/N Perangkat</label>
                                                            <input type='text' class='form-control' value='{$row['nomor_sn']}' readonly>

                                                            <label>Tanggal Pengembalian</label>
                                                            <input type='date' class='form-control' value='" . date('Y-m-d', strtotime($row['tanggal'])) . "' readonly>
                                                            
                                                            <br>
                                                            Apakah Anda yakin ingin menghapus data ini?
                                                        </div>

                                                        <div class='modal-footer'>
                                                            <form method='POST' action=''>
                                                                <input type='hidden' name='id_peminjaman' value='{$row['id_peminjaman']}'>
                                                                <button type='submit' name='hapus_pengembalian' class='btn btn-danger'>Ya, hapus!</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>";

                                        }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </main>

<!-- 4. BAGIAN FOOTER -->
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted"><h6>&copy; Copyright 2025</h6> INRAKOM BINSUA by Dimas Hafizh Widyanto Putra
                            </div>
                            <div>
                                <img src="images/Logo-BUMA1.png" alt="Perusahaan Logo" style="width: 50px; height: 50px; margin-left: 5px;">
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
    </body>
</html>
