<?php
session_start();

// Proteksi halaman - hanya admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit;
}

include '../includes/koneksi.php';

$error   = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama     = trim($_POST['nama_produk']);
    $harga    = (int) $_POST['harga'];
    $stok     = (int) $_POST['stok'];
    $deskripsi = trim($_POST['deskripsi']);

    // Validasi input
    if (empty($nama) || $harga <= 0 || $stok < 0) {
        $error = "Nama, harga, dan stok wajib diisi dengan benar.";
    } elseif (empty($_FILES['gambar']['name'])) {
        $error = "Gambar produk wajib diupload.";
    } else {
        // Validasi file gambar
        $allowed_ext  = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $allowed_mime = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        $file_ext  = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        $file_mime = mime_content_type($_FILES['gambar']['tmp_name']);

        if (!in_array($file_ext, $allowed_ext) || !in_array($file_mime, $allowed_mime)) {
            $error = "File gambar harus berformat JPG, PNG, WEBP, atau GIF.";
        } elseif ($_FILES['gambar']['size'] > 2 * 1024 * 1024) {
            $error = "Ukuran gambar maksimal 2MB.";
        } else {
            // Beri nama unik supaya tidak tertimpa
            $nama_file = uniqid('img_', true) . '.' . $file_ext;
            $tujuan    = "../uploads/" . $nama_file;

            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $tujuan)) {
                // Gunakan prepared statement
                $stmt = $conn->prepare("INSERT INTO products (nama_produk, harga, stok, deskripsi, gambar) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("siiss", $nama, $harga, $stok, $deskripsi, $nama_file);
                if ($stmt->execute()) {
                    $success = "Produk berhasil ditambahkan!";
                } else {
                    $error = "Gagal menyimpan ke database.";
                }
                $stmt->close();
            } else {
                $error = "Gagal mengupload gambar.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - JualBaju</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="navbar">
    <h2>Tambah Produk</h2>
    <a href="dashboard.php" class="btn">← Kembali</a>
</div>

<div class="form-container">
    <h2>Tambah Produk</h2>
    <?php if ($error): ?>
        <p class="error-msg"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <?php if ($success): ?>
        <p class="success-msg"><?php echo htmlspecialchars($success); ?></p>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="nama_produk" placeholder="Nama Produk" required>
        <input type="number" name="harga" placeholder="Harga" min="1" required>
        <input type="number" name="stok" placeholder="Stok" min="0" required>
        <textarea name="deskripsi" placeholder="Deskripsi produk"></textarea>
        <input type="file" name="gambar" accept="image/*" required>
        <button type="submit">Tambah Produk</button>
    </form>
</div>

</body>
</html>
