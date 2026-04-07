<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit;
}

include '../includes/koneksi.php';

$id = (int) $_GET['id'];

if ($id > 0) {
    // Ambil nama gambar dulu untuk dihapus dari folder
    $res = mysqli_query($conn, "SELECT gambar FROM products WHERE id = $id");
    $row = mysqli_fetch_assoc($res);
    if ($row) {
        $file = "../uploads/" . $row['gambar'];
        if (file_exists($file)) {
            unlink($file);
        }
    }

    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

header("Location: dashboard.php");
exit;
?>
