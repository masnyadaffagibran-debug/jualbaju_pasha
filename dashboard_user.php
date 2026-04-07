<?php
session_start();

// Proteksi halaman - harus login
if (!isset($_SESSION['role'])) {
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
    <title>Produk - JualBaju</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="navbar">
    <h2>JualBaju</h2>
    <div>
        <span style="color:white; margin-right:10px;">Halo, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
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
            <p><?php echo htmlspecialchars($row['deskripsi']); ?></p>
        </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>
