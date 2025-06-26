<?php
require_once 'config/class_peminjaman.php';
require_once 'config/class_pengembalian.php';
require_once 'config/class_kerusakan.php';
require_once 'config/class_kehilangan.php';
require_once 'config/class_login.php';

$login = new Login();

$peminjaman = new Peminjaman();
$totalPeminjaman = $peminjaman->getTotalPeminjaman();

$pengembalian = new Pengembalian();
$totalPengembalian = $pengembalian->getTotalPengembalian();

$kerusakan = new Kerusakan();
$totalKerusakan = $kerusakan->getTotalKerusakan();

$kehilangan = new Kehilangan();
$totalKehilangan = $kehilangan->getTotalKehilangan();

//Untuk validasi login
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
        <title>Dashboard | INRAKOM BINSUA</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed bg-dashboard">

    <style>
    .info-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    max-width: 440px; /* Batasi lebar maksimal */
    padding: 1rem;     /* Atur padding supaya konten pas */
}

    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.15);
    }

    .info-icon {
        font-size: 2rem;
        margin-right: 10px;
    }

    .info-card .card-body {
        display: flex;
        align-items: center;
        font-size: 1.2rem;
        font-weight: 500;
    }

    .info-value {
        font-size: 1.6rem;
        font-weight: bold;
    }
    </style>

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
                        <h1 class="mt-4"><i class="fa-solid fa-grip"></i> Dashboard</h1>
                        
                        <div class="row justify-content-center g-4 mt-4">
                            <!-- Total Peminjaman -->
                            <div class="col-xl-5 col-md-6">
                                <div class="card info-card bg-gradient bg-secondary text-white">
                                    <div class="card-body">
                                        <i class="fa-solid fa-circle-up info-icon"></i>
                                        Total Peminjaman Radio Komunikasi
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-center text-center">
                                        <a class="small text-white stretched-link" href="peminjaman.php"></a>
                                        <div class="info-value"><?php echo $totalPeminjaman; ?> Data</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Pengembalian -->
                            <div class="col-xl-5 col-md-6">
                                <div class="card info-card bg-gradient bg-success text-white">
                                    <div class="card-body">
                                        <i class="fa-solid fa-circle-down info-icon"></i>
                                        Total Pengembalian Radio Komunikasi
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-center text-center">
                                        <a class="small text-white stretched-link" href="pengembalian.php"></a>
                                        <div class="info-value"><?php echo $totalPengembalian; ?> Data</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Kerusakan -->
                            <div class="col-xl-5 col-md-6">
                                <div class="card info-card bg-gradient bg-warning text-white">
                                    <div class="card-body">
                                        <i class="fa-solid fa-screwdriver-wrench info-icon"></i>
                                        Total Kerusakan Radio Komunikasi
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-center text-center">
                                        <a class="small text-white stretched-link" href="kerusakan.php"></a>
                                        <div class="info-value"><?php echo $totalKerusakan; ?> Data</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Kehilangan -->
                            <div class="col-xl-5 col-md-6">
                                <div class="card info-card bg-gradient bg-danger text-white">
                                    <div class="card-body">
                                        <i class="fa-solid fa-ban info-icon"></i>
                                        Total Kehilangan Radio Komunikasi
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-center text-center">
                                        <a class="small text-white stretched-link" href="kehilangan.php"></a>
                                        <div class="info-value"><?php echo $totalKehilangan; ?> Data</div>
                                    </div>
                                </div>
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

<!-- 5. BAGIAN MODAL -->
    <div class="modal fade" id="tambahdatapeminjaman">
        <div class="modal-dialog">
            <div class="modal-content">

    <!-- 5.1 modal header -->
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Data Peminjaman</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

    <!-- 5.2 modal body -->
                <div class="modal-body">
                    <label>Nama Karyawan</label>
                    <input type="text" name="manpower" class="form-control">

                    <label>NIK</label>
                    <fieldset disabled>
                    <input type="text" name="nik" class="form-control" placeholder="12345678">
                    </fieldset>

                    <label>Departemen</label>
                    <fieldset disabled>
                    <input type="text" name="departemen" class="form-control" placeholder="IT">
                    </fieldset>

                    <label>Tipe Radio</label>
                    <select name="tiperadio" class="form-control">
                        <option selected>Pilih Tipe Radio</option>
                        <option value="1">HT Motorola DP338</option>
                        <option value="2">Hytera PT568</option>
                        <option value="3">HYT TC700</option>
                        <option value="3">HYT DC780</option>
                    </select>

                    <label>Nomor SN</label>
                    <input type="text" name="nomorsn" class="form-control">

                    <label>Catatan</label>
                    <input type="text" name="catatan" class="form-control">
                </div>

    <!-- 5.3 modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Submit</button>
                </div>
            </div>
        </div>        
    </div>
</html>
