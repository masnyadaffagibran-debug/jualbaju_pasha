<?php
session_start();

// Proteksi halaman - hanya admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit;
}

include '../includes/koneksi.php';
$data = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - JualBaju</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="navbar">
    <h2>Admin Panel</h2>
    <div>
        <a href="tambah_produk.php" class="btn">+ Produk</a>
        <a href="../logout.php" class="btn logout">Logout</a>
    </div>
</div>

<div class="container">
    <div class="grid">
        <?php while ($row = mysqli_fetch_assoc($data)): ?>
        <div class="card">
            <img src="../uploads/<?php echo htmlspecialchars($row['gambar']); ?>" alt="<?php echo htmlspecialchars($row['nama_produk']); ?>">
            <h3><?php echo htmlspecialchars($row['nama_produk']); ?></h3>
            <p class="price">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></p>
            <p>Stok: <?php echo (int)$row['stok']; ?></p>
            <a href="hapus_produk.php?id=<?php echo (int)$row['id']; ?>" class="btn logout" onclick="return confirm('Hapus produk ini?')">Hapus</a>
        </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>
