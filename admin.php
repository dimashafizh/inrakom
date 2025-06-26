<?php
require_once 'config/class_login.php';
$login = new Login();
$userData = $login->getCurrentUser();

// Untuk validasi login
if (!$login->isLoggedIn()) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['submit_edit_admin'])) {
    $oldPassword = $_POST['old_password'];
    $newUsername = $_POST['username'];
    $newPassword = $_POST['password'];

    $userData = $login->getCurrentUser();

    // Verifikasi password lama
    if (password_verify($oldPassword, $userData['password'])) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $update = $login->updateAdmin($newUsername, $hashedPassword);

        if ($update) {
            $_SESSION['alert'] = ['type' => 'success', 'message' => 'Berhasil memperbarui data admin'];
            header("Location: admin.php");
            exit;
        } else {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Gagal memperbarui data admin'];
        }
    } else {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Password Lama yang Anda masukkan tidak sesuai. Silahkan periksa kembali dan coba lagi'];
    }
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
        <title>Kelola Admin | INRAKOM BINSUA</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
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
                        <h1 class="mt-4"><i class="fa-solid fa-circle-user"></i> Kelola Admin</h1>

                                <?php if (isset($_SESSION['alert'])) : ?>
                                    <div class="alert alert-<?= $_SESSION['alert']['type']; ?> alert-dismissible fade show mt-3" role="alert">
                                        <?= $_SESSION['alert']['message']; ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                            <?php unset($_SESSION['alert']); ?>
                                <?php endif; ?>
                                               
                            <div class="card-body">
                                <table class="table table-bordered" id="" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Username</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Admin</td>
                                            <td>
                                                <button type="button" class="btn btn-warning mb-1" data-bs-toggle="modal" data-bs-target="#edit">Edit</button>
                                            </td>
                                        </tr>

<!-- 3.1 modal edit -->
                                            <div class="modal fade" id="edit">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">

    <!-- 3.1.1 modal edit header -->
                                                        <div class="modal-header">
                                                            <h4 class="modal-title"><i class="fa-solid fa-pen-to-square"></i> Edit Data Admin</h4>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>

    <!-- 3.1.2 modal edit body -->
                                                        <form method="POST" action="">
                                                            <div class="modal-body">
                                                                <label>Username</label>
                                                                <input type="text" name="username" class="form-control" required value="<?= htmlspecialchars($userData['username']) ?>">

                                                                <label>Password Lama</label>
                                                                <input type="password" name="old_password" class="form-control" required placeholder="Masukkan password lama">

                                                                <label>Password Baru</label>
                                                                <input type="password" name="password" class="form-control" required placeholder="Masukkan password baru">

                                                                <br>

                                                                <div class="alert alert-warning" role="alert">
                                                                Pastikan Username dan Password Baru yang Anda masukkan sudah benar sebelum menyimpan perubahan
                                                                </div>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" name="submit_edit_admin" class="btn btn-primary" onclick="return confirm ('Apakah Anda yakin ingin memperbarui data admin?')">Submit</button>
                                                            </div>
                                                        </form>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                    </tbody>
                                </table>
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
