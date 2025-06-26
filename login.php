<?php
require_once 'config/class_login.php';
$login = new Login();

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($login->loginUser($username, $password)) {
        header("Location: index.php");
        exit;
    } else {
        $error = "Username atau Password salah!";
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
        <title>Login | INRAKOM BINSUA</title>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="bg-login">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header">
                                    
                                        <h5 class="text-center font-weight-light"><img src="images/Logo-BUMA1.png" alt="Perusahaan Logo" style="width: 75px; height: 75px;"></h5><h6 class="text-center font-weight-light my-1">Inventaris Radio Komunikasi</h6></div>
                                    <div class="card-body">
                                        <form method="POST" action="">
                                            <label>Username</label>                                            
                                            <input type="text" class="form-control" name="username" required/>

                                            <label>Password</label>                                            
                                            <input type="password" class="form-control" name="password" required/>

                                            <?php if (!empty($error)) : ?>
                                                <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                                                    <?= $error; ?>
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                </div>
                                            <?php endif; ?>

                                            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">    
                                                <button type="submit" class="btn btn-success w-100">LOGIN <i class="fas fa-angle-right" style="font-size: 14px;"></i></button>
                                            </div>

                                            <div class="text-center mt-3">
                                                <small class="text-muted">&copy; 2025 INRAKOM BINSUA</small>
                                            </div>
                                        </form>
                                    </div>                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>   
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>
