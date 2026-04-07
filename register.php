<?php
session_start();
include 'includes/koneksi.php';

$error   = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $konfirmasi = $_POST['konfirmasi'];

    if (empty($username) || empty($password)) {
        $error = "Username dan password tidak boleh kosong.";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal 6 karakter.";
    } elseif ($password !== $konfirmasi) {
        $error = "Konfirmasi password tidak cocok.";
    } else {
        // Cek apakah username sudah ada
        $cek = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $cek->bind_param("s", $username);
        $cek->execute();
        $cek->store_result();

        if ($cek->num_rows > 0) {
            $error = "Username sudah digunakan.";
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'user')");
            $stmt->bind_param("ss", $username, $hash);
            if ($stmt->execute()) {
                $success = "Registrasi berhasil! <a href='index.php'>Login sekarang</a>";
            } else {
                $error = "Terjadi kesalahan, coba lagi.";
            }
            $stmt->close();
        }
        $cek->close();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - JualBaju</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="form-container">
    <h2>Register</h2>
    <?php if ($error): ?>
        <p class="error-msg"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <?php if ($success): ?>
        <p class="success-msg"><?php echo $success; ?></p>
    <?php endif; ?>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
        <input type="password" name="password" placeholder="Password (min. 6 karakter)" required>
        <input type="password" name="konfirmasi" placeholder="Konfirmasi Password" required>
        <button type="submit">Daftar</button>
    </form>
    <p style="text-align:center; margin-top:10px;">Sudah punya akun? <a href="index.php">Login</a></p>
</div>
</body>
</html>
