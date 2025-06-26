<?php
require_once 'config/class_manpower.php';
require_once 'config/class_login.php';
$login = new Login();
$manpower = new Manpower();
$dataManpower = $manpower->getAllDatamanpower();

// Untuk tambah data manpower
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tambah_manpower'])) {
    $nama_karyawan = $_POST['nama_karyawan'];
    $nik = $_POST['nik'];
    $departemen = $_POST['departemen'];
    if (!empty($nama_karyawan) && !empty($nik) && !empty($departemen)) {
        if ($manpower->cekNIK($nik)) {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Gagal menambahkan karena ditemukan data manpower yang telah didaftarkan sebelumnya'];
            header("Location: manpower.php");
            exit;
        } else {
            if ($manpower->tambahManpower($nama_karyawan, $nik, $departemen)) {
                $_SESSION['alert'] = ['type' => 'success', 'message' => 'Data manpower baru telah ditambahkan!'];
                header("Location: manpower.php");
                exit;
            } else {
                $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Gagal menambahkan data manpower baru'];
            }
        }
    } else {
        $_SESSION['alert'] = ['type' => 'warning', 'message' => 'Kolom data manpower tidak boleh kosong!'];
    }
}

// Untuk update data manpower
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_manpower'])) {
        $id_manpower = $_POST['id_manpower'];
        $nama_karyawan = $_POST['nama_karyawan'];
        $nik = $_POST['nik'];
        $departemen = $_POST['departemen'];

        if ($manpower->cekNIK($nik)) {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Gagal memperbarui karena ditemukan data mannpower yang telah didaftarkan sebelumnya'];
            header("Location: manpower.php");
            exit;
        } else {
        if ($manpower->updateManpower($id_manpower, $nama_karyawan, $nik, $departemen)) {
            $_SESSION['alert'] = ['type' => 'primary', 'message' => 'Berhasil memperbarui data manpower'];
            header("Location: manpower.php");
            exit;
        } else {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Gagal memperbarui data manpower'];
        }
    }
}

// Untuk hapus data manpower
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['hapus_manpower'])) {
    $id_manpower = $_POST['id_manpower'];
    $hapus = $manpower->hapusManpower($id_manpower);

    if ($hapus === true) {
        $_SESSION['alert'] = ['type' => 'dark', 'message' => 'Data manpower telah dihapus!'];
        header("Location: manpower.php");
        exit;
    } elseif ($hapus === 'used') {
        $_SESSION['alert'] = ['type' => 'warning', 'message' => 'Data manpower tidak bisa dihapus karena sedang digunakan dalam tabel pendataan'];
        header("Location: manpower.php");
        exit;
    } else {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Gagal menghapus data manpower'];
    }
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
        <title>Kelola Manpower | INRAKOM BINSUA</title>
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
                        <h1 class="mt-4"><i class="fa-solid fa-users"></i> Kelola Manpower</h1>
                        
                        <div class="card mb-4">
                            <div class="card-header">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahdatamanpower">
                                    Tambah Data
                                    </button>
                                <br>
                                
                                <?php if (isset($_SESSION['alert'])) : ?>
                                            <div class="alert alert-<?= $_SESSION['alert']['type']; ?> alert-dismissible fade show mt-3" role="alert">
                                                <?= $_SESSION['alert']['message']; ?>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>
                                            <?php unset($_SESSION['alert']); ?>
                                <?php endif; ?>
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Karyawan</th>
                                            <th>NIK</th>
                                            <th>Departemen</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $no = 1;
                                        foreach ($dataManpower as $row) {
                                            echo "<tr>";
                                                echo "<td>" . $no++ . "</td>";
                                                echo "<td>" . htmlspecialchars($row['nama_karyawan']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['nik']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['departemen']) . "</td>";
                                                echo "<td>
                                                        <button type='button' class='btn btn-warning mb-1' data-bs-toggle='modal' data-bs-target='#edit-{$row['id_manpower']}'>Edit</button>
                                                        <button type='button' class='btn btn-danger mb-1' data-bs-toggle='modal' data-bs-target='#hapus-{$row['id_manpower']}'>Hapus</button>
                                                    </td>";
                                            echo "</tr>";

                                            // Modal Edit untuk setiap data
                                            echo "
                                            <div class='modal fade' id='edit-{$row['id_manpower']}'>
                                                <div class='modal-dialog'>
                                                    <div class='modal-content'>
                                                        <div class='modal-header'>
                                                            <h4 class='modal-title'><i class='fa-solid fa-pen-to-square'></i> Edit Data Manpower</h4>
                                                            <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                                                        </div>
                                                        <form method='POST' action=''>
                                                            <div class='modal-body'>
                                                                <label>Nama Karyawan</label>
                                                                <input type='text' name='nama_karyawan' class='form-control' value='" . htmlspecialchars($row['nama_karyawan']) . "' required>
                                                                <input type='hidden' name='id_manpower' value='{$row['id_manpower']}'>

                                                                <label>NIK</label>
                                                                <input type='text' name='nik' class='form-control' value='" . htmlspecialchars($row['nik']) . "' required>
                                                                <input type='hidden' name='id_manpower' value='{$row['id_manpower']}'>

                                                                <label>Departemen</label>
                                                                <input type='text' name='departemen' class='form-control' value='" . htmlspecialchars($row['departemen']) . "' required>
                                                                <input type='hidden' name='id_manpower' value='{$row['id_manpower']}'>
                                                            </div>
                                                            <div class='modal-footer'>
                                                                <button type='submit' name='update_manpower' class='btn btn-primary'>Update</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>";

                                            // Modal untuk hapus data
                                            echo "
                                            <div class='modal fade' id='hapus-{$row['id_manpower']}'>
                                                <div class='modal-dialog'>
                                                    <div class='modal-content'>
                                                        <div class='modal-header'>
                                                            <h4 class='modal-title'><i class='fa-solid fa-triangle-exclamation'></i> Konfirmasi Hapus Data Manpower</h4>
                                                            <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                                                        </div>
                                                        <div class='modal-body'>
                                                            Apakah Anda yakin ingin menghapus <strong>{$row['nama_karyawan']}</strong>?
                                                        </div>
                                                        <div class='modal-footer'>
                                                            <form method='POST' action=''>
                                                                <input type='hidden' name='id_manpower' value='{$row['id_manpower']}'>
                                                                <button type='submit' name='hapus_manpower' class='btn btn-danger'>Ya, hapus!</button>
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

<!-- 5. BAGIAN MODAL TAMBAH DATA MANPOWER -->
    <div class="modal fade" id="tambahdatamanpower">
        <div class="modal-dialog">
            <div class="modal-content">

    <!-- 5.1 modal header -->
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fa-solid fa-plus"></i> Tambah Data Manpower</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

    <!-- 5.2 modal body -->
                <form method="POST" action="">
                    <div class="modal-body">
                        <label>Nama Karyawan</label>
                        <input type="text" name="nama_karyawan" class="form-control" required>
                        <label>NIK</label>
                        <input type="number" name="nik" class="form-control" required>
                        <label>Departemen</label>
                        <input type="text" name="departemen" class="form-control" required>
                    </div>

    <!-- 5.3 modal footer -->
                    <div class="modal-footer">
                        <button type="submit" name="tambah_manpower" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>        
    </div>
</html>