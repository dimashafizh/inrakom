<?php
use PHPUnit\Framework\TestCase;

require_once 'class/classLogin.php';

class LoginTest extends TestCase {
    private $pdo;
    private $login;

    protected function setUp(): void {
        $this->pdo = $this->createMock(PDO::class);
        $this->login = new Login($this->pdo);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];
    }

    // 1. Untuk test autentikasi username dan password
    public function testLoginUserBerhasil() {
        $username = 'admin';
        $password = 'BUMA123';
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->with(['username' => $username]);
        $stmt->method('rowCount')->willReturn(1);
        $stmt->method('fetch')->willReturn(['username' => $username, 'password' => $hash]);

        $this->pdo->method('prepare')->willReturn($stmt);

        $login = new Login($this->pdo);
        $this->assertTrue($login->loginUser($username, $password));
        $this->assertEquals($username, $_SESSION['user']);
    }

    public function testLoginUserGagal() {
        $username = 'admin';
        $passwordBenar = 'BUMA123';
        $passwordSalah = '123';
        $hash = password_hash($passwordBenar, PASSWORD_DEFAULT);

        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->with(['username' => $username]);
        $stmt->method('rowCount')->willReturn(1);
        $stmt->method('fetch')->willReturn(['username' => $username, 'password' => $hash]);

        $this->pdo->method('prepare')->willReturn($stmt);

        $login = new Login($this->pdo);
        $this->assertFalse($login->loginUser($username, $passwordSalah));
    }

    // 2. Untuk test login
    public function testIsLoggedInTrue() {
        $_SESSION['user'] = 'admin';
        $login = new Login($this->pdo);
        $this->assertTrue($login->isLoggedIn());
    }

    // 3. Untuk test logout
    public function testLogout() {
        $_SESSION['user'] = 'admin';
        $login = new Login($this->pdo);
        $login->logout();
        $this->assertArrayNotHasKey('user', $_SESSION);
    }

    // 4. Untuk test update akun admin
    public function testUpdateAdmin() {
        $_SESSION['user'] = 'admin';

        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->with([
            'username' => 'adminbaru',
            'password' => 'binungan123',
            'current'  => 'admin'
        ])->willReturn(true);

        $this->pdo->method('prepare')->willReturn($stmt);

        $login = new Login($this->pdo);
        $this->assertTrue($login->updateAdmin('adminbaru', 'binungan123'));
    }

    // 5. Untuk test ambil data username
    public function testGetCurrentUser() {
        $_SESSION['user'] = 'admin';

        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->with(['username' => 'admin']);
        $stmt->method('fetch')->willReturn(['username' => 'admin', 'password' => 'hashed']);

        $this->pdo->method('prepare')->willReturn($stmt);

        $login = new Login($this->pdo);
        $user = $login->getCurrentUser();

        $this->assertEquals('admin', $user['username']);
    }
}
