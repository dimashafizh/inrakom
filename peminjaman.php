<?php
require_once 'config/class_peminjaman.php';
require_once 'config/class_login.php';
$login = new Login();
$peminjaman = new Peminjaman();
$dataPeminjaman = $peminjaman->getAllDatapeminjaman();
$radioList = $peminjaman->getRadioList();

    // Untuk tambah data peminjaman
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tambah_peminjaman'])) {
        $id_manpower = $_POST['id_manpower'];
        $id_radio = $_POST['id_radio'];
        $nomor_sn = $_POST['nomor_sn'];
        $catatan = $_POST['catatan'];
        $tanggal = $_POST['tanggal'];

        if (!empty($id_manpower) && !empty($id_radio) && !empty($nomor_sn) && !empty($catatan) && !empty($tanggal)) {
            if ($peminjaman->cekNomorSN($nomor_sn)) {
                $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Gagal menambahkan data peminjaman baru karena ditemukan nomor serial perangkat yang telah terdaftar sebelumnya'];
                header("Location: peminjaman.php");
                exit;
            } else {
                if ($peminjaman->tambahPeminjaman($id_manpower, $id_radio, $nomor_sn, $catatan, $tanggal)) {
                    $_SESSION['alert'] = ['type' => 'success', 'message' => 'Data peminjaman baru telah ditambahkan!'];
                    header("Location: peminjaman.php");
                    exit;
                } else {
                    $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Gagal menambahkan data peminjaman baru'];
                }
            }
        }
    }

    // Untuk update informasi peminjaman
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_peminjaman'])) {
        $id_peminjaman = $_POST['id_peminjaman'];
        $id_radio = $_POST['id_radio'];
        $catatan = $_POST['catatan'];
        $tanggal = $_POST['tanggal'];

        if ($peminjaman->updatePeminjaman($id_peminjaman, $id_radio, $catatan, $tanggal)) {
            $_SESSION['alert'] = ['type' => 'primary', 'message' => 'Berhasil memperbarui informasi peminjaman'];
            header("Location: peminjaman.php");
            exit;
        } else {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Gagal memperbarui informasi peminjaman'];
        }
    }

    // Untuk update data S/N
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_sn'])) {
        $id_peminjaman = $_POST['id_peminjaman'];
        $nomor_sn = $_POST['nomor_sn'];

        if ($peminjaman->cekNomorSN($nomor_sn)) {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Gagal memperbarui karena ditemukan nomor serial perangkat yang telah terdaftar sebelumnya'];
            header("Location: peminjaman.php");
            exit;
        } else {
        if ($peminjaman->updateNomorSN($id_peminjaman, $nomor_sn)) {
            $_SESSION['alert'] = ['type' => 'primary', 'message' => 'Berhasil memperbarui nomor serial perangkat'];
            header("Location: peminjaman.php");
            exit;
        } else {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Gagal memperbarui nomor serial perangkat'];
        }
    }
}

// Untuk hapus data peminjaman
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['hapus_peminjaman'])) {
    $id_peminjaman = $_POST['id_peminjaman'];
    $hapus = $peminjaman->hapusPeminjaman($id_peminjaman);

    if ($hapus === true) {
        $_SESSION['alert'] = ['type' => 'dark', 'message' => 'Data peminjaman telah dihapus!'];
        header("Location: peminjaman.php");
        exit;
    } else {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Gagal menghapus data peminjaman'];
    }

    header("Location: peminjaman.php");
    exit;
}

// Untuk menampilkan data berdasarkan filter tanggal
if (isset($_POST['filter_tanggal'])) {
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_selesai = $_POST['tanggal_selesai'];
    $dataPeminjaman = $peminjaman->getDataPeminjamanByTanggal($tanggal_mulai, $tanggal_selesai);
} else {
    $dataPeminjaman = $peminjaman->getDataPeminjamanByTanggal();
}

// Untuk mengubah data peminjaman ke pengembalian
if (isset($_POST['ubah_statuspengembalian'])) {
    $id_peminjaman = $_POST['id_peminjaman'];
    $catatan_baru = $_POST['catatan'];
    $tanggal_baru = $_POST['tanggal_baru'];

    $hasil = $peminjaman->pindahKePengembalian($id_peminjaman, $catatan_baru, $tanggal_baru);

    if ($hasil) {
        $_SESSION['alert'] = ['type' => 'info', 'message' => 'Data peminjaman telah dipindahkan ke data <strong>pengembalian</strong> radio komunikasi'];
        header("Location: peminjaman.php");
        exit;
    } else {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Gagal memindahkan data peminjaman ke data <strong>pengembalian</strong> radio komunikasi'];
    }
}

// Untuk mengubah data peminjaman ke kerusakan
if (isset($_POST['ubah_statuskerusakan'])) {
    $id_peminjaman = $_POST['id_peminjaman'];
    $catatan_baru = $_POST['catatan'];
    $tanggal_baru = $_POST['tanggal_baru'];

    $hasil = $peminjaman->pindahKeKerusakan($id_peminjaman, $catatan_baru, $tanggal_baru);

    if ($hasil) {
        $_SESSION['alert'] = ['type' => 'info', 'message' => 'Data peminjaman telah dipindahkan ke data <strong>kerusakan</strong> radio komunikasi'];
        header("Location: peminjaman.php");
        exit;
    } else {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Gagal memindahkan data peminjaman ke data <strong>kerusakan</strong> radio komunikasi'];
    }
}

// Untuk mengubah data peminjaman ke kehilangan
if (isset($_POST['ubah_statuskehilangan'])) {
    $id_peminjaman = $_POST['id_peminjaman'];
    $catatan_baru = $_POST['catatan'];
    $tanggal_baru = $_POST['tanggal_baru'];

    $hasil = $peminjaman->pindahKeKehilangan($id_peminjaman, $catatan_baru, $tanggal_baru);

    if ($hasil) {
        $_SESSION['alert'] = ['type' => 'info', 'message' => 'Data peminjaman telah dipindahkan ke data <strong>kehilangan</strong> radio komunikasi'];
        header("Location: peminjaman.php");
        exit;
    } else {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Gagal memindahkan data peminjaman ke data <strong>kehilangan</strong> radio komunikasi'];
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
        <title>Peminjaman | INRAKOM BINSUA</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3-beta2/dist/css/bootsrap.min.css" rel="stylesheet" crossorigin="anonymous">
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

        <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    </head>
    <body class="sb-nav-fixed bg-utama">

        <style>
            .ui-autocomplete {
                z-index: 2147483647;
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
                        <h1 class="mt-4"><i class="fa-solid fa-circle-up"></i> Data Peminjaman Radio Komunikasi</h1>
                        
                        <div class="card mb-4">
                            <div class="card-header">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahdatapeminjaman">
                                Tambah Data
                                </button>
                                <a href="exportdata-peminjaman.php" class="btn btn-secondary">Export Data</a>
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
                                            <th>Edit</th>
                                            <th>Aksi</th>
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
                                                echo "<td>
                                                        <button type='button' class='btn btn-warning mb-1' data-bs-toggle='modal' data-bs-target='#edit-{$row['id_peminjaman']}'>Info</button>
                                                        <button type='button' class='btn btn-info mb-1' data-bs-toggle='modal' data-bs-target='#editsn-{$row['id_peminjaman']}'>S/N</button>
                                                        
                                                    </td>";
                                                echo "<td>
                                                <button type='button' class='btn btn-success mb-1' data-bs-toggle='modal' data-bs-target='#ubahstatus-{$row['id_peminjaman']}'>Status</button>
                                                        <button type='button' class='btn btn-danger mb-1' data-bs-toggle='modal' data-bs-target='#hapus-{$row['id_peminjaman']}'>Hapus</button>
                                                ";
                                            echo "</tr>";

                                            // Modal Edit untuk data catatan
                                            echo "
                                            <div class='modal fade' id='edit-{$row['id_peminjaman']}'>
                                                <div class='modal-dialog'>
                                                    <div class='modal-content'>
                                                        <div class='modal-header'>
                                                            <h4 class='modal-title'><i class='fa-solid fa-pen-to-square'></i> Edit Informasi Peminjaman</h4>
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
                                                                
                                                                <label>Catatan</label>
                                                                <input type='text' name='catatan' class='form-control' value='" . htmlspecialchars($row['catatan']) . "' required>
                                                                <input type='hidden' name='id_peminjaman' value='{$row['id_peminjaman']}'>

                                                                <label>Tanggal Peminjaman</label>
                                                                <input type='date' name='tanggal' class='form-control' value='" . date('Y-m-d', strtotime($row['tanggal'])) . "' required>
                                                            </div>
                                                            <div class='modal-footer'>
                                                                <button type='submit' name='update_peminjaman' class='btn btn-primary'>Update</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>";

                                            // Modal Edit untuk data S/N
                                            echo "
                                            <div class='modal fade' id='editsn-{$row['id_peminjaman']}'>
                                                <div class='modal-dialog'>
                                                    <div class='modal-content'>
                                                        <div class='modal-header'>
                                                            <h4 class='modal-title'><i class='fa-solid fa-pen-to-square'></i> Edit S/N Radio Komunikasi</h4>
                                                            <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                                                        </div>
                                                        <form method='POST' action=''>
                                                            <div class='modal-body'>

                                                                <label>S/N Perangkat</label>
                                                                <input type='text' name='nomor_sn' class='form-control' value='" . htmlspecialchars($row['nomor_sn']) . "' required>
                                                                <input type='hidden' name='id_peminjaman' value='{$row['id_peminjaman']}'>

                                                            </div>
                                                            <div class='modal-footer'>
                                                                <button type='submit' name='update_sn' class='btn btn-primary'>Update</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>";

                                            // Modal untuk hapus data
                                            echo "
                                            <div class='modal fade' id='hapus-{$row['id_peminjaman']}'>
                                                <div class='modal-dialog'>
                                                    <div class='modal-content'>
                                                        <div class='modal-header'>
                                                            <h4 class='modal-title'><i class='fa-solid fa-triangle-exclamation'></i> Konfirmasi Hapus Data Peminjaman</h4>
                                                            <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                                                        </div>

                                                        <div class='modal-body'>
                                                            <label>Nama Karyawan</label>
                                                            <input type='text' class='form-control' value='{$row['nama_karyawan']}' readonly>
                                                                
                                                            <label>Tipe Radio HT</label>
                                                            <input type='text' class='form-control' value='{$row['tipe_radio']}' readonly>
                                                                
                                                            <label>S/N Perangkat</label>
                                                            <input type='text' class='form-control' value='{$row['nomor_sn']}' readonly>

                                                            <label>Tanggal Peminjaman</label>
                                                            <input type='date' class='form-control' value='" . date('Y-m-d', strtotime($row['tanggal'])) . "' readonly>
                                                            
                                                            <br>
                                                            
                                                            Apakah Anda yakin ingin menghapus data ini?
                                                            
                                                        </div>

                                                        <div class='modal-footer'>
                                                            <form method='POST' action=''>
                                                                <input type='hidden' name='id_peminjaman' value='{$row['id_peminjaman']}'>
                                                                <button type='submit' name='hapus_peminjaman' class='btn btn-danger'>Ya, hapus!</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>";

                                            // Modal untuk mengubah status
                                            echo "
                                            <div class='modal fade' id='ubahstatus-{$row['id_peminjaman']}'>
                                                <div class='modal-dialog'>
                                                    <div class='modal-content'>
                                                        <div class='modal-header'>
                                                            <h4 class='modal-title'><i class='fa-solid fa-right-from-bracket'></i> Ubah Status Data Peminjaman</h4>
                                                            <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                                                        </div>

                                                        <div class='modal-body'>
                                                            <label>Nama Karyawan</label>
                                                            <fieldset disabled>
                                                            <input type='text' class='form-control' value='{$row['nama_karyawan']}' readonly>
                                                            </fieldset>
                                                                
                                                            <label>Tipe Radio HT</label>
                                                            <fieldset disabled>
                                                            <input type='text' class='form-control' value='{$row['tipe_radio']}' readonly>
                                                            </fieldset>
                                                                
                                                            <label>S/N Perangkat</label>
                                                            <fieldset disabled>
                                                            <input type='text' class='form-control' value='{$row['nomor_sn']}' readonly>
                                                            </fieldset>

                                                            <fieldset disabled>
                                                            <label>Tanggal Peminjaman</label>
                                                            <input type='date' name='' class='form-control' value='" . date('Y-m-d', strtotime($row['tanggal'])) . "'>
                                                            </fieldset>
                                                            
                                                        </div>

                                                        <div class='modal-body'>

                                                            <div class='alert alert-warning' role='alert'>
                                                                Lengkapi kolom di bawah ini dengan informasi terbaru
                                                            </div>

                                                            <form method='POST' action=''>

                                                                <label>Catatan</label>
                                                                <input type='text' name='catatan' class='form-control' value='" . htmlspecialchars($row['catatan']) . "' required>
                                                                <input type='hidden' name='id_peminjaman' value='{$row['id_peminjaman']}'>

                                                                <label>Tanggal (Kembali/Rusak/Hilang)</label>
                                                                <input type='date' name='tanggal_baru' class='form-control' required>
                                                                
                                                                <br>

                                                                <div class='modal-footer'>
                                                                    <button type='submit' name='ubah_statuspengembalian' class='btn btn-success' onclick='return confirm (\"Apakah Anda yakin ingin memindahkan data ini ke data pengembalian radio komunikasi?\")'>Pengembalian</button>

                                                                    <button type='submit' name='ubah_statuskerusakan' class='btn btn-warning' onclick='return confirm (\"Apakah Anda yakin ingin memindahkan data ini ke data kerusakan radio komunikasi?\")'>Kerusakan</button>

                                                                    <button type='submit' name='ubah_statuskehilangan' class='btn btn-danger' onclick='return confirm (\"Apakah Anda yakin ingin memindahkan data ini ke data kehilangan radio komunikasi?\")'>Kehilangan</button>
                                                                </div>
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

    <script> // Bagian untuk memanggil data karyawan pada kolom nama karyawan di modal box tambah peminjaman
    $(document).ready(function() {
        $("#manpower").autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: "config/get_data_nama_karyawan.php",
                    type: 'POST',
                    dataType: "json",
                    data: {
                        nama: request.term
                    },
                    success: function (data) {
                        response(data);
                    }
                });
            },
            autoFocus: true,
            appendTo: "#tambahdatapeminjaman",
            select: function (event, ui) {
                
                $('#nik').val(ui.item.nik);
                $('#departemen').val(ui.item.departemen);
                $('#id_manpower').val(ui.item.id_manpower);
            }
        });
    });
    </script>

    <script> // Bagian untuk memanggil data nik pada kolom nik di modal box tambah peminjaman
    $(document).ready(function() {
        $("#nik").autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: "config/get_data_nik.php",
                    type: 'POST',
                    dataType: "json",
                    data: {
                        nomornik: request.term
                    },
                    success: function (data) {
                        response(data);
                    }
                });
            },
            autoFocus: true,
            appendTo: "#modal-fullscreen",
            select: function (event, ui) {
                
                $('#manpower').val(ui.item.nama_karyawan);
                $('#departemen').val(ui.item.departemen);
                $('#id_manpower').val(ui.item.id_manpower);
            }
        });
    });
    </script>

<!-- 5. BAGIAN MODAL TAMBAH DATA PEMINJAMAN -->
    <div class="modal fade" id="tambahdatapeminjaman">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fa-solid fa-plus"></i> Tambah Data Peminjaman</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- Modal Body dengan Form -->
                <form method="POST" action="">
                    <div class="modal-body">
                        <label>Nama Karyawan</label>
                        <input type="text" id="manpower" class="form-control" required>

                        <input type="hidden" name="id_manpower" id="id_manpower">

                        <label>NIK</label>
                        <input type="number" name="" id="nik" class="form-control" required>

                        <label>Departemen</label>
                        <input type="text" name="" id="departemen" class="form-control" value="" readonly>
                        

                        <label>Tipe Radio HT</label>
                        <select name="id_radio" class="form-control" required>
                            <option value="" selected disabled>Pilih Tipe Radio HT</option>
                            <?php foreach ($radioList as $radio): ?>
                                <option value="<?= htmlspecialchars($radio['id_radio']) ?>">
                                    <?= htmlspecialchars($radio['tipe_radio']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <label>S/N Perangkat</label>
                        <input type="text" name="nomor_sn" class="form-control" required>

                        <label>Catatan</label>
                        <input type="text" name="catatan" class="form-control">

                        <label>Tanggal Peminjaman</label>
                        <input type="date" name="tanggal" class="form-control" required>
                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="submit" name="tambah_peminjaman" class="btn btn-primary">Submit</button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</html>
