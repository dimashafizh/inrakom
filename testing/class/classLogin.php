<?php
require_once 'database.php';

class Login {
    protected $conn;

    public function __construct($conn = null) {
        $this->conn = $conn ?? Database::connect();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // 1. Untuk autentikasi username dan password
    public function loginUser($username, $password) {
        $sqllogin = "SELECT * FROM user WHERE username = :username LIMIT 1";
        $login = $this->conn->prepare($sqllogin);
        $login->execute(['username' => $username]);

        if ($login->rowCount() > 0) {
            $user = $login->fetch(PDO::FETCH_ASSOC);
            
            if (password_verify($password, $user['password'])) {
                $_SESSION['user'] = $user['username'];
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // 2. Untuk login
    public function isLoggedIn() {
        return isset($_SESSION['user']);
    }

    // 3. Untuk logout
    public function logout() {
        session_destroy();
        unset($_SESSION['user']);
    }

    // 4. Untuk update akun admin
    public function updateAdmin($username, $hashedPassword) {
        $sqlupdateakun = "UPDATE user SET username = :username, password = :password WHERE username = :current";
        $updateakun = $this->conn->prepare($sqlupdateakun);
        return $updateakun->execute([
            'username' => $username,
            'password' => $hashedPassword,
            'current' => $_SESSION['user']
        ]);
    }

    // 5. Untuk ambil data username
    public function getCurrentUser() {
        $sqlambilakun = "SELECT * FROM user WHERE username = :username LIMIT 1";
        $ambilakun = $this->conn->prepare($sqlambilakun);
        $ambilakun->execute(['username' => $_SESSION['user']]);
        return $ambilakun->fetch(PDO::FETCH_ASSOC);
    }

}
?>
